<?php

declare(strict_types=1);

namespace Essabu\Tests\Common;

use Essabu\Common\Exception\EssabuException;
use Essabu\Common\Exception\ForbiddenException;
use Essabu\Common\Exception\NotFoundException;
use Essabu\Common\Exception\RateLimitException;
use Essabu\Common\Exception\ServerException;
use Essabu\Common\Exception\UnauthorizedException;
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
    private function createHttpClient(MockHandler $mock, int $maxRetries = 0): HttpClient
    {
        $config = new EssabuConfig(
            apiKey: 'test-key',
            tenantId: 'test-tenant',
            maxRetries: $maxRetries,
        );

        $handlerStack = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handlerStack]);

        return new HttpClient($config, $guzzle);
    }

    // ---------------------------------------------------------------
    // Successful responses
    // ---------------------------------------------------------------

    public function testGetReturnsDecodedJson(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"id":"1","name":"Test"}'),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->get('/test');
        $this->assertSame(['id' => '1', 'name' => 'Test'], $result);
    }

    public function testGetWithQueryParameters(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"items":[]}'),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->get('/test', ['page' => 0, 'size' => 20]);
        $this->assertSame(['items' => []], $result);
    }

    public function testPostReturnsDecodedJson(): void
    {
        $mock = new MockHandler([
            new Response(201, [], '{"id":"new-1","created":true}'),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->post('/test', ['name' => 'New Item']);
        $this->assertSame(['id' => 'new-1', 'created' => true], $result);
    }

    public function testPostVoidDoesNotReturnBody(): void
    {
        $mock = new MockHandler([
            new Response(204),
        ]);
        $client = $this->createHttpClient($mock);

        // Should not throw
        $client->postVoid('/test/action');
        $this->assertTrue(true);
    }

    public function testPutReturnsDecodedJson(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"id":"1","updated":true}'),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->put('/test/1', ['name' => 'Updated']);
        $this->assertSame(['id' => '1', 'updated' => true], $result);
    }

    public function testPatchReturnsDecodedJson(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"id":"1","patched":true}'),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->patch('/test/1', ['name' => 'Patched']);
        $this->assertSame(['id' => '1', 'patched' => true], $result);
    }

    public function testDeleteVoid(): void
    {
        $mock = new MockHandler([
            new Response(204),
        ]);
        $client = $this->createHttpClient($mock);

        $client->delete('/test/1');
        $this->assertTrue(true);
    }

    public function testDeleteWithResponse(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"deleted":true}'),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->deleteWithResponse('/test/1');
        $this->assertSame(['deleted' => true], $result);
    }

    public function testGetBytesReturnsRawContent(): void
    {
        $binaryContent = 'PDF_BINARY_CONTENT_HERE';
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/pdf'], $binaryContent),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->getBytes('/test/1/pdf');
        $this->assertSame($binaryContent, $result);
    }

    public function test204ReturnsEmptyArray(): void
    {
        $mock = new MockHandler([
            new Response(204),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->get('/test');
        $this->assertSame([], $result);
    }

    public function testEmptyBodyReturnsEmptyArray(): void
    {
        $mock = new MockHandler([
            new Response(200, [], ''),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->get('/test');
        $this->assertSame([], $result);
    }

    public function testInvalidJsonReturnsEmptyArray(): void
    {
        $mock = new MockHandler([
            new Response(200, [], 'not-json'),
        ]);
        $client = $this->createHttpClient($mock);

        $result = $client->get('/test');
        $this->assertSame([], $result);
    }

    // ---------------------------------------------------------------
    // Error handling
    // ---------------------------------------------------------------

    public function test400ThrowsValidationException(): void
    {
        $mock = new MockHandler([
            new Response(400, [], '{"violations":{"email":"Invalid email"}}'),
        ]);
        $client = $this->createHttpClient($mock);

        $this->expectException(ValidationException::class);
        $client->post('/test', ['email' => 'bad']);
    }

    public function test422ThrowsValidationExceptionWithFieldErrors(): void
    {
        $body = '{"errors":{"firstName":"Required","email":"Must be valid"}}';
        $mock = new MockHandler([
            new Response(422, [], $body),
        ]);
        $client = $this->createHttpClient($mock);

        try {
            $client->post('/test', []);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertSame(422, $e->statusCode);
            $this->assertArrayHasKey('firstName', $e->fieldErrors);
            $this->assertArrayHasKey('email', $e->fieldErrors);
        }
    }

    public function test401ThrowsUnauthorizedException(): void
    {
        $mock = new MockHandler([
            new Response(401, [], '{"message":"Unauthorized"}'),
        ]);
        $client = $this->createHttpClient($mock);

        $this->expectException(UnauthorizedException::class);
        $client->get('/test');
    }

    public function test403ThrowsForbiddenException(): void
    {
        $mock = new MockHandler([
            new Response(403, [], '{"message":"Forbidden"}'),
        ]);
        $client = $this->createHttpClient($mock);

        $this->expectException(ForbiddenException::class);
        $client->get('/test');
    }

    public function test404ThrowsNotFoundException(): void
    {
        $mock = new MockHandler([
            new Response(404, [], '{"message":"Not found"}'),
        ]);
        $client = $this->createHttpClient($mock);

        $this->expectException(NotFoundException::class);
        $client->get('/test/nonexistent');
    }

    public function test429ThrowsRateLimitException(): void
    {
        $mock = new MockHandler([
            new Response(429, ['Retry-After' => '30'], '{"message":"Rate limited"}'),
        ]);
        $client = $this->createHttpClient($mock);

        try {
            $client->get('/test');
            $this->fail('Expected RateLimitException');
        } catch (RateLimitException $e) {
            $this->assertSame(429, $e->statusCode);
            $this->assertSame(30.0, $e->retryAfter);
        }
    }

    public function test429DefaultRetryAfter(): void
    {
        $mock = new MockHandler([
            new Response(429, [], '{}'),
        ]);
        $client = $this->createHttpClient($mock);

        try {
            $client->get('/test');
            $this->fail('Expected RateLimitException');
        } catch (RateLimitException $e) {
            $this->assertSame(60.0, $e->retryAfter);
        }
    }

    public function test500ThrowsServerExceptionAfterRetries(): void
    {
        // With 0 retries, should fail immediately
        $mock = new MockHandler([
            new Response(500, [], '{"error":"Internal Server Error"}'),
        ]);
        $client = $this->createHttpClient($mock, maxRetries: 0);

        $this->expectException(ServerException::class);
        $client->get('/test');
    }

    public function test500RetriesBeforeFailing(): void
    {
        // 2 retries + 1 initial = 3 attempts, all fail
        $mock = new MockHandler([
            new Response(500, [], '{"error":"fail"}'),
            new Response(500, [], '{"error":"fail"}'),
            new Response(500, [], '{"error":"fail"}'),
        ]);
        $client = $this->createHttpClient($mock, maxRetries: 2);

        $this->expectException(ServerException::class);
        $client->get('/test');
    }

    public function test500RetriesAndSucceeds(): void
    {
        // First attempt fails, second succeeds
        $mock = new MockHandler([
            new Response(500, [], '{"error":"fail"}'),
            new Response(200, [], '{"ok":true}'),
        ]);
        $client = $this->createHttpClient($mock, maxRetries: 2);

        $result = $client->get('/test');
        $this->assertSame(['ok' => true], $result);
    }

    public function testUnexpectedStatusThrowsEssabuException(): void
    {
        $mock = new MockHandler([
            new Response(418, [], '{"message":"I am a teapot"}'),
        ]);
        $client = $this->createHttpClient($mock);

        $this->expectException(ServerException::class);
        $client->get('/test');
    }

    // ---------------------------------------------------------------
    // Exception hierarchy
    // ---------------------------------------------------------------

    public function testAllExceptionsExtendEssabuException(): void
    {
        $this->assertInstanceOf(EssabuException::class, new ValidationException('test', 400));
        $this->assertInstanceOf(EssabuException::class, new UnauthorizedException());
        $this->assertInstanceOf(EssabuException::class, new ForbiddenException());
        $this->assertInstanceOf(EssabuException::class, new NotFoundException());
        $this->assertInstanceOf(EssabuException::class, new RateLimitException('test'));
        $this->assertInstanceOf(EssabuException::class, new ServerException('test'));
    }

    public function testEssabuExceptionExtendsRuntimeException(): void
    {
        $this->assertInstanceOf(\RuntimeException::class, new EssabuException('test'));
    }

    public function testValidationExceptionFieldErrors(): void
    {
        $e = new ValidationException('Validation failed', 422, ['email' => 'Invalid']);
        $this->assertSame(422, $e->statusCode);
        $this->assertSame(['email' => 'Invalid'], $e->fieldErrors);
    }

    public function testRateLimitExceptionRetryAfter(): void
    {
        $e = new RateLimitException('Rate limited', 429, 45.0);
        $this->assertSame(45.0, $e->retryAfter);
    }

    public function testServerExceptionWithPrevious(): void
    {
        $previous = new \RuntimeException('connection failed');
        $e = new ServerException('Communication error', 0, $previous);
        $this->assertSame($previous, $e->getPrevious());
    }
}
