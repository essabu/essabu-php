<?php

declare(strict_types=1);

namespace Essabu\Tests;

use Essabu\Essabu;
use Essabu\EssabuConfig;
use Essabu\Accounting\AccountingClient;
use Essabu\Asset\AssetClient;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;
use Essabu\EInvoice\EInvoiceClient;
use Essabu\Hr\HrClient;
use Essabu\Hr\Api\EmployeeApi;
use Essabu\Identity\IdentityClient;
use Essabu\Payment\PaymentClient;
use Essabu\Project\ProjectClient;
use Essabu\Trade\TradeClient;
use PHPUnit\Framework\TestCase;

final class EssabuTest extends TestCase
{
    // ---------------------------------------------------------------
    // Constructor validation
    // ---------------------------------------------------------------

    public function testConstructorRequiresApiKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('apiKey is required');
        new Essabu('', 'tenant-1');
    }

    public function testConstructorRequiresTenantId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('tenantId is required');
        new Essabu('key-1', '');
    }

    public function testCreatesClientSuccessfully(): void
    {
        $essabu = new Essabu('test-key', 'test-tenant');
        $this->assertInstanceOf(Essabu::class, $essabu);
    }

    public function testConstructorWithAllOptions(): void
    {
        $essabu = new Essabu(
            apiKey: 'test-key',
            tenantId: 'test-tenant',
            baseUrl: 'https://custom.api.com',
            connectTimeout: 10.0,
            readTimeout: 60.0,
            maxRetries: 5,
        );
        $this->assertInstanceOf(Essabu::class, $essabu);
    }

    // ---------------------------------------------------------------
    // Config
    // ---------------------------------------------------------------

    public function testFromConfig(): void
    {
        $config = new EssabuConfig(
            apiKey: 'test-key',
            tenantId: 'test-tenant',
            baseUrl: 'https://custom.api.com',
        );
        $essabu = Essabu::fromConfig($config);
        $this->assertInstanceOf(Essabu::class, $essabu);
    }

    public function testConfigDefaultValues(): void
    {
        $config = new EssabuConfig(apiKey: 'key', tenantId: 'tenant');
        $this->assertSame('https://api.essabu.com', $config->baseUrl);
        $this->assertSame(5.0, $config->connectTimeout);
        $this->assertSame(30.0, $config->readTimeout);
        $this->assertSame(3, $config->maxRetries);
    }

    public function testConfigRejectsEmptyApiKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new EssabuConfig(apiKey: '', tenantId: 'tenant');
    }

    public function testConfigRejectsEmptyTenantId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new EssabuConfig(apiKey: 'key', tenantId: '');
    }

    // ---------------------------------------------------------------
    // Module accessors
    // ---------------------------------------------------------------

    public function testModuleAccessorsReturnCorrectTypes(): void
    {
        $essabu = new Essabu('test-key', 'test-tenant');

        $this->assertInstanceOf(HrClient::class, $essabu->hr);
        $this->assertInstanceOf(AccountingClient::class, $essabu->accounting);
        $this->assertInstanceOf(IdentityClient::class, $essabu->identity);
        $this->assertInstanceOf(TradeClient::class, $essabu->trade);
        $this->assertInstanceOf(PaymentClient::class, $essabu->payment);
        $this->assertInstanceOf(EInvoiceClient::class, $essabu->eInvoice);
        $this->assertInstanceOf(ProjectClient::class, $essabu->project);
        $this->assertInstanceOf(AssetClient::class, $essabu->asset);
    }

    public function testModuleAccessorsAreCached(): void
    {
        $essabu = new Essabu('test-key', 'test-tenant');

        $hr1 = $essabu->hr;
        $hr2 = $essabu->hr;
        $this->assertSame($hr1, $hr2);

        $accounting1 = $essabu->accounting;
        $accounting2 = $essabu->accounting;
        $this->assertSame($accounting1, $accounting2);
    }

    public function testAllModulesAreCachedIndependently(): void
    {
        $essabu = new Essabu('test-key', 'test-tenant');

        $hr = $essabu->hr;
        $accounting = $essabu->accounting;
        $identity = $essabu->identity;
        $trade = $essabu->trade;
        $payment = $essabu->payment;
        $eInvoice = $essabu->eInvoice;
        $project = $essabu->project;
        $asset = $essabu->asset;

        // Each module is a distinct object
        $modules = [$hr, $accounting, $identity, $trade, $payment, $eInvoice, $project, $asset];
        $objectIds = array_map(fn ($m) => spl_object_id($m), $modules);
        $this->assertCount(8, array_unique($objectIds), 'All 8 modules must be distinct instances');
    }

    public function testSubModuleAccessorsWork(): void
    {
        $essabu = new Essabu('test-key', 'test-tenant');

        $this->assertInstanceOf(EmployeeApi::class, $essabu->hr->employees);
    }

    public function testUnknownModuleThrows(): void
    {
        $essabu = new Essabu('test-key', 'test-tenant');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown module: nonExistent');
        $essabu->nonExistent;
    }

    // ---------------------------------------------------------------
    // Pagination models
    // ---------------------------------------------------------------

    public function testPageRequestOf(): void
    {
        $page = PageRequest::of(2, 50);
        $this->assertSame(2, $page->page);
        $this->assertSame(50, $page->size);
        $this->assertSame('page=2&size=50', $page->toQueryString());
    }

    public function testPageRequestFirst(): void
    {
        $page = PageRequest::first();
        $this->assertSame(0, $page->page);
        $this->assertSame(20, $page->size);
        $this->assertSame('page=0&size=20', $page->toQueryString());
    }

    public function testPageRequestWithSort(): void
    {
        $sorted = new PageRequest(page: 0, size: 10, sort: 'name', direction: 'asc');
        $this->assertSame('page=0&size=10&sort=name,asc', $sorted->toQueryString());
    }

    public function testPageRequestSortWithoutDirection(): void
    {
        $sorted = new PageRequest(page: 0, size: 10, sort: 'createdAt');
        $this->assertSame('page=0&size=10&sort=createdAt', $sorted->toQueryString());
    }

    public function testPageResponseFromArray(): void
    {
        $data = [
            'content' => [['id' => '1'], ['id' => '2']],
            'page' => 0,
            'size' => 20,
            'totalElements' => 2,
            'totalPages' => 1,
            'first' => true,
            'last' => true,
        ];
        $response = PageResponse::fromArray($data);

        $this->assertTrue($response->hasContent());
        $this->assertFalse($response->hasNext());
        $this->assertFalse($response->hasPrevious());
        $this->assertSame(2, $response->totalElements);
        $this->assertSame(1, $response->totalPages);
        $this->assertCount(2, $response->content);
    }

    public function testPageResponseMiddlePage(): void
    {
        $data = [
            'content' => [['id' => '3']],
            'page' => 1,
            'size' => 1,
            'totalElements' => 3,
            'totalPages' => 3,
            'first' => false,
            'last' => false,
        ];
        $response = PageResponse::fromArray($data);

        $this->assertTrue($response->hasContent());
        $this->assertTrue($response->hasNext());
        $this->assertTrue($response->hasPrevious());
    }

    public function testPageResponseEmpty(): void
    {
        $data = [
            'content' => [],
            'page' => 0,
            'size' => 20,
            'totalElements' => 0,
            'totalPages' => 0,
            'first' => true,
            'last' => true,
        ];
        $response = PageResponse::fromArray($data);

        $this->assertFalse($response->hasContent());
        $this->assertFalse($response->hasNext());
        $this->assertSame(0, $response->totalElements);
    }

    public function testPageResponseFromPartialArray(): void
    {
        $response = PageResponse::fromArray([]);

        $this->assertSame(0, $response->page);
        $this->assertSame(20, $response->size);
        $this->assertSame(0, $response->totalElements);
        $this->assertSame([], $response->content);
    }
}
