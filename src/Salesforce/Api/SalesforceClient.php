<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Salesforce\Api;

use Pipetic\Bundle\Salesforce\Auth\AccessToken;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Lightweight Salesforce REST API client.
 *
 * Wraps the most commonly used Salesforce REST API endpoints:
 * - SOQL queries
 * - sObject CRUD (create / update / delete)
 *
 * HTTP communication is delegated to a PSR-18 compatible client so that the
 * consuming application can supply its preferred HTTP implementation
 * (Guzzle, Symfony HttpClient, etc.).
 *
 * The client does NOT handle token refresh automatically. Callers should
 * check {@see AccessToken::isExpired()} and call
 * {@see \Pipetic\Bundle\Salesforce\Auth\OAuthConnector::refreshToken()} before
 * instantiating or updating the client.
 */
class SalesforceClient
{
    /**
     * Default Salesforce REST API version.
     */
    public const DEFAULT_API_VERSION = 'v59.0';

    public function __construct(
        private AccessToken $token,
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly string $apiVersion = self::DEFAULT_API_VERSION,
    ) {
    }

    /**
     * Replace the current access token (e.g. after a refresh).
     */
    public function withToken(AccessToken $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Execute a SOQL query and return all records.
     *
     * Automatically follows the `nextRecordsUrl` pagination so that all
     * matching records are returned regardless of the default page size.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \RuntimeException
     */
    public function query(string $soql): array
    {
        $url      = $this->buildUrl('/services/data/' . $this->apiVersion . '/query');
        $url     .= '?' . http_build_query(['q' => $soql]);
        $records  = [];

        do {
            $response = $this->get($url);
            $records  = array_merge($records, $response['records'] ?? []);
            $url      = isset($response['nextRecordsUrl'])
                ? $this->buildBaseUrl() . $response['nextRecordsUrl']
                : null;
        } while ($url !== null && !($response['done'] ?? true));

        return $records;
    }

    /**
     * Create a new sObject record.
     *
     * @param array<string, mixed> $data
     *
     * @return string The ID of the newly created record.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \RuntimeException
     */
    public function create(string $objectType, array $data): string
    {
        $url      = $this->buildUrl('/services/data/' . $this->apiVersion . '/sobjects/' . $objectType);
        $response = $this->post($url, $data);

        return $response['id'] ?? throw new \RuntimeException('Salesforce create did not return an ID.');
    }

    /**
     * Update an existing sObject record.
     *
     * @param array<string, mixed> $data
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \RuntimeException
     */
    public function update(string $objectType, string $id, array $data): void
    {
        $url = $this->buildUrl(
            '/services/data/' . $this->apiVersion . '/sobjects/' . $objectType . '/' . $id
        );

        $this->patch($url, $data);
    }

    /**
     * Delete an sObject record by ID.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \RuntimeException
     */
    public function delete(string $objectType, string $id): void
    {
        $url = $this->buildUrl(
            '/services/data/' . $this->apiVersion . '/sobjects/' . $objectType . '/' . $id
        );

        $request = $this->requestFactory
            ->createRequest('DELETE', $url)
            ->withHeader('Authorization', 'Bearer ' . $this->token->getAccessToken())
            ->withHeader('Accept', 'application/json');

        $response = $this->httpClient->sendRequest($request);
        $this->assertSuccessStatus($response->getStatusCode(), (string) $response->getBody());
    }

    /**
     * @return array<string, mixed>
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function get(string $url): array
    {
        $request = $this->requestFactory
            ->createRequest('GET', $url)
            ->withHeader('Authorization', 'Bearer ' . $this->token->getAccessToken())
            ->withHeader('Accept', 'application/json');

        $response = $this->httpClient->sendRequest($request);
        $body     = (string) $response->getBody();

        $this->assertSuccessStatus($response->getStatusCode(), $body);

        return json_decode($body, true) ?? [];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function post(string $url, array $data): array
    {
        $body    = json_encode($data);
        $request = $this->requestFactory
            ->createRequest('POST', $url)
            ->withHeader('Authorization', 'Bearer ' . $this->token->getAccessToken())
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json')
            ->withBody($this->streamFactory->createStream($body));

        $response = $this->httpClient->sendRequest($request);
        $bodyStr  = (string) $response->getBody();

        $this->assertSuccessStatus($response->getStatusCode(), $bodyStr);

        return json_decode($bodyStr, true) ?? [];
    }

    /**
     * @param array<string, mixed> $data
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function patch(string $url, array $data): void
    {
        $body    = json_encode($data);
        $request = $this->requestFactory
            ->createRequest('PATCH', $url)
            ->withHeader('Authorization', 'Bearer ' . $this->token->getAccessToken())
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json')
            ->withBody($this->streamFactory->createStream($body));

        $response = $this->httpClient->sendRequest($request);
        $this->assertSuccessStatus($response->getStatusCode(), (string) $response->getBody());
    }

    private function buildBaseUrl(): string
    {
        return rtrim($this->token->getInstanceUrl(), '/');
    }

    private function buildUrl(string $path): string
    {
        return $this->buildBaseUrl() . $path;
    }

    /**
     * @throws \RuntimeException
     */
    private function assertSuccessStatus(int $status, string $body): void
    {
        if ($status >= 200 && $status < 300) {
            return;
        }

        $payload = json_decode($body, true);
        $message = is_array($payload)
            ? ($payload[0]['message'] ?? $payload['message'] ?? $body)
            : $body;

        throw new \RuntimeException(
            sprintf('Salesforce API error (HTTP %d): %s', $status, $message)
        );
    }
}
