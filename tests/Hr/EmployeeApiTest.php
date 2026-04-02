<?php

declare(strict_types=1);

namespace Essabu\Tests\Hr;

use Essabu\Common\HttpClient;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;
use Essabu\EssabuConfig;
use Essabu\Hr\Api\EmployeeApi;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class EmployeeApiTest extends TestCase
{
    private function createApi(MockHandler $mock): EmployeeApi
    {
        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $config = new EssabuConfig('test-key', 'test-tenant');
        $httpClient = new HttpClient($config, $guzzle);

        return new EmployeeApi($httpClient);
    }

    public function testListEmployees(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'items' => [
                    ['id' => '1', 'firstName' => 'Jean'],
                    ['id' => '2', 'firstName' => 'Marie'],
                ],
                'totalItems' => 2,
            ])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->list();

        self::assertInstanceOf(PageResponse::class, $result);
        self::assertCount(2, $result->items);
        self::assertSame(2, $result->totalItems);
    }

    public function testListWithPagination(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'items' => [['id' => '1']],
                'totalItems' => 50,
            ])),
        ]);

        $api = $this->createApi($mock);
        $request = new PageRequest(page: 2, itemsPerPage: 10, search: 'Jean');
        $result = $api->list($request);

        self::assertSame(50, $result->totalItems);
    }

    public function testGetEmployee(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'id' => '123',
                'firstName' => 'Jean',
                'lastName' => 'Mukendi',
            ])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->get('123');

        self::assertSame('123', $result['id']);
        self::assertSame('Jean', $result['firstName']);
    }

    public function testCreateEmployee(): void
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode([
                'id' => 'new-id',
                'firstName' => 'Jean',
                'lastName' => 'Mukendi',
            ])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->create([
            'firstName' => 'Jean',
            'lastName' => 'Mukendi',
        ]);

        self::assertSame('new-id', $result['id']);
    }

    public function testUpdateEmployee(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'id' => '123',
                'firstName' => 'Jean-Pierre',
            ])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->update('123', ['firstName' => 'Jean-Pierre']);

        self::assertSame('Jean-Pierre', $result['firstName']);
    }

    public function testDeleteEmployee(): void
    {
        $mock = new MockHandler([
            new Response(204, [], ''),
        ]);

        $api = $this->createApi($mock);
        $result = $api->delete('123');

        self::assertSame([], $result);
    }

    public function testTerminateEmployee(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => '123', 'status' => 'terminated'])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->terminate('123', ['reason' => 'End of contract']);

        self::assertSame('terminated', $result['status']);
    }
}
