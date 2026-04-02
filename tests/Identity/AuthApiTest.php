<?php

declare(strict_types=1);

namespace Essabu\Tests\Identity;

use Essabu\Common\HttpClient;
use Essabu\EssabuConfig;
use Essabu\Identity\Api\AuthApi;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class AuthApiTest extends TestCase
{
    private function createApi(MockHandler $mock): AuthApi
    {
        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $config = new EssabuConfig('test-key', 'test-tenant');
        $httpClient = new HttpClient($config, $guzzle);

        return new AuthApi($httpClient);
    }

    public function testLogin(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'token' => 'jwt-token',
                'refreshToken' => 'refresh-token',
                'user' => ['id' => 'u1', 'email' => 'jean@essabu.com'],
            ])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->login([
            'email' => 'jean@essabu.com',
            'password' => 'secret',
        ]);

        self::assertSame('jwt-token', $result['token']);
        self::assertArrayHasKey('user', $result);
    }

    public function testLogout(): void
    {
        $mock = new MockHandler([
            new Response(204, [], ''),
        ]);

        $api = $this->createApi($mock);
        $result = $api->logout();

        self::assertSame([], $result);
    }

    public function testRefreshToken(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'token' => 'new-jwt-token',
                'refreshToken' => 'new-refresh-token',
            ])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->refresh('old-refresh-token');

        self::assertSame('new-jwt-token', $result['token']);
    }

    public function testForgotPassword(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['message' => 'Email sent'])),
        ]);

        $api = $this->createApi($mock);
        $result = $api->forgotPassword('jean@essabu.com');

        self::assertSame('Email sent', $result['message']);
    }
}
