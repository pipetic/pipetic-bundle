<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Salesforce\Auth;

/**
 * Salesforce OAuth2 configuration value object.
 *
 * Holds all credentials and settings required to perform the OAuth2 flow
 * against a Salesforce organisation. A separate instance should be created
 * per tenant in multi-tenant deployments.
 */
class OAuthConfig
{
    /**
     * Salesforce production login URL.
     */
    public const LOGIN_URL = 'https://login.salesforce.com';

    /**
     * Salesforce sandbox login URL.
     */
    public const SANDBOX_URL = 'https://test.salesforce.com';

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $redirectUri,
        private readonly string $loginUrl = self::LOGIN_URL,
    ) {
    }

    public static function create(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        string $loginUrl = self::LOGIN_URL,
    ): self {
        return new self($clientId, $clientSecret, $redirectUri, $loginUrl);
    }

    public static function forSandbox(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
    ): self {
        return new self($clientId, $clientSecret, $redirectUri, self::SANDBOX_URL);
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    public function getLoginUrl(): string
    {
        return $this->loginUrl;
    }

    public function getAuthorizationUrl(): string
    {
        return rtrim($this->loginUrl, '/') . '/services/oauth2/authorize';
    }

    public function getTokenUrl(): string
    {
        return rtrim($this->loginUrl, '/') . '/services/oauth2/token';
    }

    public function isSandbox(): bool
    {
        return $this->loginUrl === self::SANDBOX_URL;
    }
}
