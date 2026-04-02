<?php

declare(strict_types=1);

namespace Essabu\Tests;

use Essabu\Accounting\AccountingClient;
use Essabu\Asset\AssetClient;
use Essabu\EInvoice\EInvoiceClient;
use Essabu\Essabu;
use Essabu\Hr\HrClient;
use Essabu\Identity\IdentityClient;
use Essabu\Payment\PaymentClient;
use Essabu\Project\ProjectClient;
use Essabu\Trade\TradeClient;
use PHPUnit\Framework\TestCase;

final class EssabuTest extends TestCase
{
    private Essabu $essabu;

    protected function setUp(): void
    {
        $this->essabu = new Essabu('test-api-key', 'test-tenant-id');
    }

    public function testConfigIsSet(): void
    {
        $config = $this->essabu->getConfig();
        self::assertSame('test-api-key', $config->apiKey);
        self::assertSame('test-tenant-id', $config->tenantId);
        self::assertSame('https://api.essabu.com', $config->baseUrl);
    }

    public function testConfigWithCustomOptions(): void
    {
        $essabu = new Essabu('key', 'tenant', [
            'baseUrl' => 'https://custom.api.com',
            'timeout' => 60,
            'retries' => 5,
            'apiVersion' => 'v2',
        ]);

        $config = $essabu->getConfig();
        self::assertSame('https://custom.api.com', $config->baseUrl);
        self::assertSame(60, $config->timeout);
        self::assertSame(5, $config->retries);
        self::assertSame('v2', $config->apiVersion);
    }

    public function testBuildUrl(): void
    {
        $config = $this->essabu->getConfig();
        self::assertSame('https://api.essabu.com/api/v1/hr/employees', $config->buildUrl('hr/employees'));
    }

    public function testHrModuleAccess(): void
    {
        self::assertInstanceOf(HrClient::class, $this->essabu->hr);
    }

    public function testAccountingModuleAccess(): void
    {
        self::assertInstanceOf(AccountingClient::class, $this->essabu->accounting);
    }

    public function testIdentityModuleAccess(): void
    {
        self::assertInstanceOf(IdentityClient::class, $this->essabu->identity);
    }

    public function testTradeModuleAccess(): void
    {
        self::assertInstanceOf(TradeClient::class, $this->essabu->trade);
    }

    public function testPaymentModuleAccess(): void
    {
        self::assertInstanceOf(PaymentClient::class, $this->essabu->payment);
    }

    public function testEInvoiceModuleAccess(): void
    {
        self::assertInstanceOf(EInvoiceClient::class, $this->essabu->eInvoice);
    }

    public function testProjectModuleAccess(): void
    {
        self::assertInstanceOf(ProjectClient::class, $this->essabu->project);
    }

    public function testAssetModuleAccess(): void
    {
        self::assertInstanceOf(AssetClient::class, $this->essabu->asset);
    }

    public function testLazyInitialization(): void
    {
        $hr1 = $this->essabu->hr;
        $hr2 = $this->essabu->hr;
        self::assertSame($hr1, $hr2);
    }

    public function testUnknownModuleThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown module: nonexistent');
        $this->essabu->nonexistent;
    }
}
