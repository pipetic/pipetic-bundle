<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Tests\Salesforce\Auth;

use PHPUnit\Framework\TestCase;
use Pipetic\Bundle\Salesforce\Auth\OAuthConfig;

class OAuthConfigTest extends TestCase
{
    public function testCreateProduction(): void
    {
        $config = OAuthConfig::create('my_client', 'my_secret', 'https://app.example.com/callback');

        $this->assertSame('my_client', $config->getClientId());
        $this->assertSame('my_secret', $config->getClientSecret());
        $this->assertSame('https://app.example.com/callback', $config->getRedirectUri());
        $this->assertSame(OAuthConfig::LOGIN_URL, $config->getLoginUrl());
        $this->assertFalse($config->isSandbox());
    }

    public function testCreateSandbox(): void
    {
        $config = OAuthConfig::forSandbox('my_client', 'my_secret', 'https://app.example.com/callback');

        $this->assertSame(OAuthConfig::SANDBOX_URL, $config->getLoginUrl());
        $this->assertTrue($config->isSandbox());
    }

    public function testAuthorizationUrl(): void
    {
        $config = OAuthConfig::create('id', 'secret', 'https://example.com/cb');

        $this->assertSame(
            'https://login.salesforce.com/services/oauth2/authorize',
            $config->getAuthorizationUrl()
        );
    }

    public function testTokenUrl(): void
    {
        $config = OAuthConfig::create('id', 'secret', 'https://example.com/cb');

        $this->assertSame(
            'https://login.salesforce.com/services/oauth2/token',
            $config->getTokenUrl()
        );
    }

    public function testSandboxAuthorizationUrl(): void
    {
        $config = OAuthConfig::forSandbox('id', 'secret', 'https://example.com/cb');

        $this->assertSame(
            'https://test.salesforce.com/services/oauth2/authorize',
            $config->getAuthorizationUrl()
        );
    }
}
