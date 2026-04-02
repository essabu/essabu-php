<?php

declare(strict_types=1);

namespace Essabu\Tests\Common;

use Essabu\Common\Exception\AuthenticationException;
use Essabu\Common\Exception\AuthorizationException;
use Essabu\Common\Exception\NotFoundException;
use Essabu\Common\Exception\RateLimitException;
use Essabu\Common\Exception\ServerException;
use Essabu\Common\Exception\ValidationException;
use Essabu\Common\HttpClient;
use Essabu\EssabuConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class HttpClientTest extends TestCase
{
    private function createClient(MockHandler $mock): HttpClient
    {
        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $config = new EssabuConfig('test-key', 'test-tenant');

        return new HttpClient($config, $guzzle);
    }

    public function testGetRequest(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => '123', 'name' => 'Test'])),
        ]);

        $client = $this->createClient($mock);
        $result = $client->get('hr/employees/123');

        self::assertSame('123', $result['id']);
        self::assertSame('Test', $result['name']);
    }

    public function testPostRequest(): void
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode(['id' => 'new-id', 'firstName' => 'Jean'])),
        ]);

        $client = $this->createClient($mock);
        $result = $client->post('hr/employees', ['firstName' => 'Jean']);

        self::assertSame('new-id', $result['id']);
    }

    public function testDeleteReturnsEmptyOnNoContent(): void
    {
        $mock = new MockHandler([
            new Response(204, [], ''),
        ]);

        $client = $this->createClient($mock);
        $result = $client->delete('hr/employees/123');

        self::assertSame([], $result);
    }

    public function testMaps401ToAuthenticationException(): void
    {
        $mock = new MockHandler([
            new Response(401, [], json_encode(['message' => 'Invalid token'])),
        ]);

        $client = $this->createClient($mock);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid token');
        $client->get('hr/employees');
    }

    public function testMaps403ToAuthorizationException(): void
    {
        $mock = new MockHandler([
            new Response(403, [], json_encode(['message' => 'Forbidden'])),
        ]);

        $client = $this->createClient($mock);

        $this->expectException(AuthorizationException::class);
        $client->get('hr/employees');
    }

    public function testMaps404ToNotFoundException(): void
    {
        $mock = new MockHandler([
            new Response(404, [], json_encode(['message' => 'Not found'])),
        ]);

        $client = $this->createClient($mock);

        $this->expectException(NotFoundException::class);
        $client->get('hr/employees/missing');
    }

    public function testMaps422ToValidationException(): void
    {
        $mock = new MockHandler([
            new Response(422, [], json_encode([
                'message' => 'Validation failed',
                'violations' => ['firstName' => ['Required']],
            ])),
        ]);

        $client = $this->createClient($mock);

        $this->expectException(ValidationException::class);
        $client->post('hr/employees', []);
    }

    public function testMaps429ToRateLimitException(): void
    {
        $mock = new MockHandler([
            new Response(429, ['Retry-After' => '60'], json_encode(['message' => 'Too many requests'])),
        ]);

        $client = $this->createClient($mock);

        try {
            $client->get('hr/employees');
            self::fail('Expected RateLimitException');
        } catch (RateLimitException $e) {
            self::assertSame(60, $e->getRetryAfter());
            self::assertSame(429, $e->getHttpStatusCode());
        }
    }

    public function testMaps500ToServerException(): void
    {
        $mock = new MockHandler([
            new Response(500, [], json_encode(['message' => 'Internal error'])),
        ]);

        $client = $this->createClient($mock);

        $this->expectException(ServerException::class);
        $client->get('hr/employees');
    }
}
