<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Tests\Salesforce\Auth;

use PHPUnit\Framework\TestCase;
use Pipetic\Bundle\Salesforce\Auth\AccessToken;

class AccessTokenTest extends TestCase
{
    private function makeTokenData(array $overrides = []): array
    {
        return array_merge([
            'access_token'  => 'abc123',
            'refresh_token' => 'refresh456',
            'instance_url'  => 'https://myorg.salesforce.com',
            'token_type'    => 'Bearer',
            'issued_at'     => (string) (time() * 1000),
            'id'            => 'https://login.salesforce.com/id/00Dxx/005xx',
            'scope'         => 'api refresh_token',
        ], $overrides);
    }

    public function testFromArray(): void
    {
        $token = AccessToken::fromArray($this->makeTokenData());

        $this->assertSame('abc123', $token->getAccessToken());
        $this->assertSame('refresh456', $token->getRefreshToken());
        $this->assertSame('https://myorg.salesforce.com', $token->getInstanceUrl());
        $this->assertSame('Bearer', $token->getTokenType());
        $this->assertSame('api refresh_token', $token->getScope());
        $this->assertNotNull($token->getExpiresAt());
    }

    public function testFromArrayMissingAccessTokenThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        AccessToken::fromArray(['instance_url' => 'https://myorg.salesforce.com']);
    }

    public function testFromArrayMissingInstanceUrlThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        AccessToken::fromArray(['access_token' => 'abc123']);
    }

    public function testIsExpiredFalseForFreshToken(): void
    {
        $token = AccessToken::fromArray($this->makeTokenData());
        $this->assertFalse($token->isExpired());
    }

    public function testIsExpiredTrueForOldToken(): void
    {
        // issued_at in the far past → expiresAt is in the past
        $data  = $this->makeTokenData(['issued_at' => '1000000']);
        $token = AccessToken::fromArray($data);
        $this->assertTrue($token->isExpired());
    }

    public function testIsExpiredFalseWhenNoExpiresAt(): void
    {
        $data  = $this->makeTokenData();
        unset($data['issued_at']);
        $token = AccessToken::fromArray($data);
        $this->assertFalse($token->isExpired());
    }

    public function testToArrayRoundTrip(): void
    {
        $original    = AccessToken::fromArray($this->makeTokenData());
        $stored      = $original->toArray();
        $restored    = AccessToken::fromStoredArray($stored);

        $this->assertSame($original->getAccessToken(), $restored->getAccessToken());
        $this->assertSame($original->getRefreshToken(), $restored->getRefreshToken());
        $this->assertSame($original->getInstanceUrl(), $restored->getInstanceUrl());
        $this->assertSame($original->getExpiresAt(), $restored->getExpiresAt());
    }
}
