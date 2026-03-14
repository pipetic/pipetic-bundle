<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Salesforce\Auth;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Handles the Salesforce OAuth2 authorisation flow.
 *
 * Responsibilities:
 * - Building the authorisation redirect URL
 * - Exchanging an authorisation code for an access token
 * - Refreshing an expired access token
 *
 * HTTP communication is delegated to a PSR-18 compatible client so that the
 * consuming application can supply its preferred HTTP implementation.
 */
class OAuthConnector
{
    /**
     * Default OAuth2 scopes requested from Salesforce.
     */
    public const DEFAULT_SCOPES = ['api', 'refresh_token', 'offline_access'];

    public function __construct(
        private readonly OAuthConfig $config,
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {
    }

    /**
     * Build the Salesforce authorisation URL to which the user should be
     * redirected to begin the OAuth2 flow.
     *
     * @param string[]    $scopes Additional scopes to request.
     * @param string|null $state  CSRF state token (recommended).
     */
    public function getAuthorizationUrl(array $scopes = [], ?string $state = null): string
    {
        $params = [
            'client_id'     => $this->config->getClientId(),
            'redirect_uri'  => $this->config->getRedirectUri(),
            'response_type' => 'code',
            'scope'         => implode(' ', array_unique(array_merge(self::DEFAULT_SCOPES, $scopes))),
        ];

        if ($state !== null) {
            $params['state'] = $state;
        }

        return $this->config->getAuthorizationUrl() . '?' . http_build_query($params);
    }

    /**
     * Exchange an authorisation code (received via the redirect callback)
     * for an AccessToken.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface On HTTP error.
     * @throws \RuntimeException                         On API error response.
     */
    public function exchangeCodeForToken(string $code): AccessToken
    {
        $body = http_build_query([
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->config->getClientId(),
            'client_secret' => $this->config->getClientSecret(),
            'redirect_uri'  => $this->config->getRedirectUri(),
            'code'          => $code,
        ]);

        return $this->requestToken($body);
    }

    /**
     * Use a refresh token to obtain a new access token.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface On HTTP error.
     * @throws \RuntimeException                         On API error response.
     */
    public function refreshToken(string $refreshToken): AccessToken
    {
        $body = http_build_query([
            'grant_type'    => 'refresh_token',
            'client_id'     => $this->config->getClientId(),
            'client_secret' => $this->config->getClientSecret(),
            'refresh_token' => $refreshToken,
        ]);

        return $this->requestToken($body);
    }

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \RuntimeException
     */
    private function requestToken(string $body): AccessToken
    {
        $request = $this->requestFactory
            ->createRequest('POST', $this->config->getTokenUrl())
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withHeader('Accept', 'application/json')
            ->withBody($this->streamFactory->createStream($body));

        $response = $this->httpClient->sendRequest($request);

        $payload = json_decode((string) $response->getBody(), true);

        if (!is_array($payload)) {
            throw new \RuntimeException('Invalid JSON response from Salesforce token endpoint.');
        }

        if (isset($payload['error'])) {
            throw new \RuntimeException(
                sprintf(
                    'Salesforce token error: %s – %s',
                    $payload['error'],
                    $payload['error_description'] ?? ''
                )
            );
        }

        return AccessToken::fromArray($payload);
    }
}
