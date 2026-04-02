<?php

declare(strict_types=1);

namespace Essabu\Tests\Accounting;

use Essabu\Accounting\Api\InvoiceApi;
use Essabu\Common\HttpClient;
use Essabu\EssabuConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class InvoiceApiTest extends TestCase
{
    private function createApi(MockHandler $mock): InvoiceApi
    {
        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $config = new EssabuConfig('test-key', 'test-tenant');
        $httpClient = new HttpClient($config, $guzzle);

        return new InvoiceApi($httpClient);
    }

    public function testCreateInvoice(): void
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode([
                'id' => 'inv-1',
                'number' => 'INV-2026-001',
                'status' => 'draft',
            ])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->create([
            'customerId' => 'cust-1',
            'lines' => [
                ['description' => 'Service', 'amount' => 1000],
            ],
        ]);

        self::assertSame('inv-1', $result['id']);
        self::assertSame('draft', $result['status']);
    }

    public function testFinalizeInvoice(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 'inv-1', 'status' => 'finalized'])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->finalize('inv-1');

        self::assertSame('finalized', $result['status']);
    }

    public function testSendInvoice(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 'inv-1', 'sentAt' => '2026-03-26T10:00:00Z'])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->send('inv-1');

        self::assertArrayHasKey('sentAt', $result);
    }

    public function testVoidInvoice(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 'inv-1', 'status' => 'voided'])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->void('inv-1');

        self::assertSame('voided', $result['status']);
    }

    public function testMarkAsPaid(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 'inv-1', 'status' => 'paid'])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->markAsPaid('inv-1', ['paidAt' => '2026-03-26']);

        self::assertSame('paid', $result['status']);
    }
}
