<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Salesforce\Auth;

/**
 * Salesforce OAuth2 access token value object.
 *
 * Encapsulates all token data returned by Salesforce after a successful
 * OAuth2 authorisation, including the instance URL required to make
 * authenticated API calls to the tenant's Salesforce org.
 */
class AccessToken
{
    public function __construct(
        private readonly string $accessToken,
        private readonly string $refreshToken,
        private readonly string $instanceUrl,
        private readonly string $tokenType = 'Bearer',
        private readonly ?int $expiresAt = null,
        private readonly ?string $identityUrl = null,
        private readonly ?string $scope = null,
    ) {
    }

    /**
     * Create an AccessToken from the raw Salesforce token response array.
     */
    public static function fromArray(array $data): self
    {
        $expiresAt = null;
        if (isset($data['issued_at'])) {
            // Salesforce returns issued_at in milliseconds.
            $expiresAt = (int) ((int) $data['issued_at'] / 1000) + 7200;
        }

        return new self(
            accessToken: $data['access_token'] ?? throw new \InvalidArgumentException('Missing access_token'),
            refreshToken: $data['refresh_token'] ?? '',
            instanceUrl: $data['instance_url'] ?? throw new \InvalidArgumentException('Missing instance_url'),
            tokenType: $data['token_type'] ?? 'Bearer',
            expiresAt: $expiresAt,
            identityUrl: $data['id'] ?? null,
            scope: $data['scope'] ?? null,
        );
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getInstanceUrl(): string
    {
        return $this->instanceUrl;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresAt(): ?int
    {
        return $this->expiresAt;
    }

    public function getIdentityUrl(): ?string
    {
        return $this->identityUrl;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * Returns true when the access token has expired or is about to expire.
     *
     * A 60-second buffer is applied so tokens are refreshed before they
     * actually expire.
     */
    public function isExpired(): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }

        return time() >= ($this->expiresAt - 60);
    }

    /**
     * Serialize to array for storage (e.g. in a DataNode's metadata field).
     */
    public function toArray(): array
    {
        return [
            'access_token'  => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'instance_url'  => $this->instanceUrl,
            'token_type'    => $this->tokenType,
            'expires_at'    => $this->expiresAt,
            'id'            => $this->identityUrl,
            'scope'         => $this->scope,
        ];
    }

    /**
     * Restore an AccessToken that was previously stored via toArray().
     */
    public static function fromStoredArray(array $data): self
    {
        return new self(
            accessToken: $data['access_token'] ?? throw new \InvalidArgumentException('Missing access_token'),
            refreshToken: $data['refresh_token'] ?? '',
            instanceUrl: $data['instance_url'] ?? throw new \InvalidArgumentException('Missing instance_url'),
            tokenType: $data['token_type'] ?? 'Bearer',
            expiresAt: isset($data['expires_at']) ? (int) $data['expires_at'] : null,
            identityUrl: $data['id'] ?? null,
            scope: $data['scope'] ?? null,
        );
    }
}
