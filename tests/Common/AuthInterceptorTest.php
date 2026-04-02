<?php

declare(strict_types=1);

namespace Essabu\Tests\Common;

use Essabu\Common\AuthInterceptor;
use Essabu\EssabuConfig;
use PHPUnit\Framework\TestCase;

final class AuthInterceptorTest extends TestCase
{
    public function testGetHeadersContainsBearerToken(): void
    {
        $config = new EssabuConfig(apiKey: 'sk_test_abc123', tenantId: 'tenant_xyz');
        $interceptor = new AuthInterceptor($config);

        $headers = $interceptor->getHeaders();

        $this->assertSame('Bearer sk_test_abc123', $headers['Authorization']);
    }

    public function testGetHeadersContainsTenantId(): void
    {
        $config = new EssabuConfig(apiKey: 'sk_test_abc123', tenantId: 'tenant_xyz');
        $interceptor = new AuthInterceptor($config);

        $headers = $interceptor->getHeaders();

        $this->assertSame('tenant_xyz', $headers['X-Tenant-Id']);
    }

    public function testGetHeadersContainsAcceptJson(): void
    {
        $config = new EssabuConfig(apiKey: 'key', tenantId: 'tenant');
        $interceptor = new AuthInterceptor($config);

        $headers = $interceptor->getHeaders();

        $this->assertSame('application/json', $headers['Accept']);
    }

    public function testGetHeadersReturnsExactlyThreeHeaders(): void
    {
        $config = new EssabuConfig(apiKey: 'key', tenantId: 'tenant');
        $interceptor = new AuthInterceptor($config);

        $headers = $interceptor->getHeaders();

        $this->assertCount(3, $headers);
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertArrayHasKey('X-Tenant-Id', $headers);
        $this->assertArrayHasKey('Accept', $headers);
    }

    public function testDifferentConfigsProduceDifferentHeaders(): void
    {
        $config1 = new EssabuConfig(apiKey: 'key1', tenantId: 'tenant1');
        $config2 = new EssabuConfig(apiKey: 'key2', tenantId: 'tenant2');

        $interceptor1 = new AuthInterceptor($config1);
        $interceptor2 = new AuthInterceptor($config2);

        $this->assertNotSame(
            $interceptor1->getHeaders()['Authorization'],
            $interceptor2->getHeaders()['Authorization'],
        );
        $this->assertNotSame(
            $interceptor1->getHeaders()['X-Tenant-Id'],
            $interceptor2->getHeaders()['X-Tenant-Id'],
        );
    }
}
