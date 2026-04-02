<?php

declare(strict_types=1);

namespace Essabu\Tests\Accounting;

use Essabu\Accounting\AccountingClient;
use Essabu\Accounting\Api\Transactions\InvoiceApi;
use Essabu\Common\HttpClient;
use Essabu\Common\Model\PageRequest;
use PHPUnit\Framework\TestCase;

final class InvoiceApiTest extends TestCase
{
    private HttpClient $httpMock;
    private InvoiceApi $api;

    protected function setUp(): void
    {
        $this->httpMock = $this->createMock(HttpClient::class);
        $this->api = new InvoiceApi($this->httpMock);
    }

    // ---------------------------------------------------------------
    // CRUD
    // ---------------------------------------------------------------

    public function testCreateInvoice(): void
    {
        $input = [
            'customerId' => 'cust_1',
            'companyId'  => 'comp_1',
            'currency'   => 'USD',
            'items'      => [
                ['description' => 'Consulting', 'quantity' => 1, 'unitPrice' => 5000],
            ],
        ];
        $expected = array_merge(['id' => 'inv_1', 'number' => 'INV-001', 'status' => 'draft'], $input);

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/api/accounting/invoices', $input)
            ->willReturn($expected);

        $result = $this->api->create($input);

        $this->assertSame('inv_1', $result['id']);
        $this->assertSame('draft', $result['status']);
    }

    public function testGetInvoice(): void
    {
        $expected = ['id' => 'inv_1', 'number' => 'INV-001'];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/accounting/invoices/inv_1')
            ->willReturn($expected);

        $result = $this->api->get('inv_1');

        $this->assertSame('INV-001', $result['number']);
    }

    public function testListInvoices(): void
    {
        $expected = [['id' => 'inv_1'], ['id' => 'inv_2']];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/accounting/invoices?companyId=comp_1')
            ->willReturn($expected);

        $result = $this->api->list('comp_1');

        $this->assertCount(2, $result);
    }

    public function testListInvoicesWithPagination(): void
    {
        $page = PageRequest::of(0, 10);
        $expected = [['id' => 'inv_1']];

        $this->httpMock
            ->expects($this->once())
            ->method('get')
            ->with('/api/accounting/invoices?companyId=comp_1&page=0&size=10')
            ->willReturn($expected);

        $result = $this->api->list('comp_1', $page);

        $this->assertCount(1, $result);
    }

    public function testUpdateInvoice(): void
    {
        $input = ['notes' => 'Updated notes'];
        $expected = ['id' => 'inv_1', 'notes' => 'Updated notes'];

        $this->httpMock
            ->expects($this->once())
            ->method('put')
            ->with('/api/accounting/invoices/inv_1', $input)
            ->willReturn($expected);

        $result = $this->api->update('inv_1', $input);

        $this->assertSame('Updated notes', $result['notes']);
    }

    public function testDeleteInvoice(): void
    {
        $this->httpMock
            ->expects($this->once())
            ->method('delete')
            ->with('/api/accounting/invoices/inv_1');

        $this->api->delete('inv_1');
    }

    // ---------------------------------------------------------------
    // Lifecycle actions
    // ---------------------------------------------------------------

    public function testFinalizeInvoice(): void
    {
        $expected = ['id' => 'inv_1', 'status' => 'finalized', 'number' => 'INV-2026-0001'];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/api/accounting/invoices/inv_1/finalize')
            ->willReturn($expected);

        $result = $this->api->finalize('inv_1');

        $this->assertSame('finalized', $result['status']);
    }

    public function testSendInvoice(): void
    {
        $expected = ['id' => 'inv_1', 'status' => 'sent', 'sentAt' => '2026-03-26T10:00:00Z'];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/api/accounting/invoices/inv_1/send')
            ->willReturn($expected);

        $result = $this->api->send('inv_1');

        $this->assertSame('sent', $result['status']);
    }

    public function testMarkAsPaid(): void
    {
        $expected = ['id' => 'inv_1', 'status' => 'paid'];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/api/accounting/invoices/inv_1/mark-as-paid')
            ->willReturn($expected);

        $result = $this->api->markAsPaid('inv_1');

        $this->assertSame('paid', $result['status']);
    }

    public function testCancelInvoice(): void
    {
        $expected = ['id' => 'inv_1', 'status' => 'cancelled'];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/api/accounting/invoices/inv_1/cancel')
            ->willReturn($expected);

        $result = $this->api->cancel('inv_1');

        $this->assertSame('cancelled', $result['status']);
    }

    public function testDownloadPdf(): void
    {
        $pdfContent = '%PDF-1.4 binary content here';

        $this->httpMock
            ->expects($this->once())
            ->method('getBytes')
            ->with('/api/accounting/invoices/inv_1/pdf')
            ->willReturn($pdfContent);

        $result = $this->api->downloadPdf('inv_1');

        $this->assertSame($pdfContent, $result);
    }

    // ---------------------------------------------------------------
    // Recurring invoices
    // ---------------------------------------------------------------

    public function testCreateRecurringInvoice(): void
    {
        $input = [
            'customerId' => 'cust_1',
            'frequency'  => 'monthly',
            'items'      => [['description' => 'Hosting', 'unitPrice' => 49.99]],
        ];
        $expected = array_merge(['id' => 'rec_1', 'status' => 'active'], $input);

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/api/accounting/invoices/recurring', $input)
            ->willReturn($expected);

        $result = $this->api->createRecurring($input);

        $this->assertSame('rec_1', $result['id']);
    }

    public function testActivateRecurring(): void
    {
        $expected = ['id' => 'rec_1', 'status' => 'active'];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/api/accounting/invoices/recurring/rec_1/activate')
            ->willReturn($expected);

        $result = $this->api->activateRecurring('rec_1');

        $this->assertSame('active', $result['status']);
    }

    public function testDeactivateRecurring(): void
    {
        $expected = ['id' => 'rec_1', 'status' => 'inactive'];

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with('/api/accounting/invoices/recurring/rec_1/deactivate')
            ->willReturn($expected);

        $result = $this->api->deactivateRecurring('rec_1');

        $this->assertSame('inactive', $result['status']);
    }

    // ---------------------------------------------------------------
    // AccountingClient accessor
    // ---------------------------------------------------------------

    public function testAccountingClientExposesInvoiceApi(): void
    {
        $client = new AccountingClient($this->httpMock);
        $this->assertInstanceOf(InvoiceApi::class, $client->invoices);
    }
}
