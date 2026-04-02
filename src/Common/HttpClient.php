<?php

declare(strict_types=1);

namespace Essabu\Common;

use Essabu\Common\Exception\EssabuException;
use Essabu\Common\Exception\ForbiddenException;
use Essabu\Common\Exception\NotFoundException;
use Essabu\Common\Exception\RateLimitException;
use Essabu\Common\Exception\ServerException;
use Essabu\Common\Exception\UnauthorizedException;
use Essabu\Common\Exception\ValidationException;
use Essabu\EssabuConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * Shared HTTP client wrapper used by all module clients.
 *
 * @internal Not intended for direct use by SDK consumers.
 */
class HttpClient
{
    private readonly Client $client;
    private readonly string $baseUrl;
    private readonly AuthInterceptor $auth;
    private readonly int $maxRetries;

    private const SDK_VERSION = '1.0.0';
    private const BASE_DELAY_S = 1.0;

    /**
     * @param EssabuConfig $config SDK configuration
     * @param Client|null $client  Optional Guzzle client (for testing)
     */
    public function __construct(EssabuConfig $config, ?Client $client = null)
    {
        $this->baseUrl = rtrim($config->baseUrl, '/');
        $this->auth = new AuthInterceptor($config);
        $this->maxRetries = $config->maxRetries;
        $this->client = $client ?? new Client([
            'connect_timeout' => $config->connectTimeout,
            'timeout' => $config->readTimeout,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $path, array $query = []): array
    {
        $url = $path;
        if ($query !== []) {
            $separator = str_contains($path, '?') ? '&' : '?';
            $url .= $separator . http_build_query($query);
        }
        $response = $this->execute('GET', $url);
        return $this->decodeJson($response);
    }

    public function getBytes(string $path): string
    {
        $response = $this->execute('GET', $path);
        return $response->getBody()->getContents();
    }

    /**
     * @param array<string, mixed>|null $body
     * @return array<string, mixed>
     */
    public function post(string $path, ?array $body = null): array
    {
        $response = $this->execute('POST', $path, $body);
        return $this->decodeJson($response);
    }

    /**
     * @param array<string, mixed>|null $body
     */
    public function postVoid(string $path, ?array $body = null): void
    {
        $this->execute('POST', $path, $body);
    }

    /**
     * @param array<string, mixed>|null $body
     * @return array<string, mixed>
     */
    public function put(string $path, ?array $body = null): array
    {
        $response = $this->execute('PUT', $path, $body);
        return $this->decodeJson($response);
    }

    /**
     * @param array<string, mixed>|null $body
     * @return array<string, mixed>
     */
    public function patch(string $path, ?array $body = null): array
    {
        $response = $this->execute('PATCH', $path, $body);
        return $this->decodeJson($response);
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteWithResponse(string $path): array
    {
        $response = $this->execute('DELETE', $path);
        return $this->decodeJson($response);
    }

    public function delete(string $path): void
    {
        $this->execute('DELETE', $path);
    }

    /**
     * @param array<string, mixed> $multipart
     * @return array<string, mixed>
     */
    public function postMultipart(string $path, array $multipart): array
    {
        $headers = $this->auth->getHeaders();
        unset($headers['Content-Type']);

        $response = $this->client->request('POST', $this->baseUrl . $path, [
            'headers' => $headers,
            'multipart' => $multipart,
        ]);

        return $this->decodeJson($response);
    }

    /**
     * Execute an HTTP request with optional body, retries, and error handling.
     *
     * @param array<string, mixed>|null $body
     */
    private function execute(string $method, string $path, ?array $body = null): ResponseInterface
    {
        $url = $this->baseUrl . $path;
        $options = [
            'headers' => array_merge($this->auth->getHeaders(), [
                'Content-Type' => 'application/json',
                'User-Agent' => 'essabu-php/' . self::SDK_VERSION,
            ]),
        ];

        if ($body !== null) {
            $options['json'] = $body;
        }

        $attempt = 0;

        while (true) {
            try {
                $response = $this->client->request($method, $url, $options);
            } catch (ConnectException $e) {
                throw new ServerException('Communication error with the Essabu API', 0, $e);
            } catch (RequestException $e) {
                $response = $e->getResponse();
                if ($response === null) {
                    throw new ServerException('Communication error with the Essabu API', 0, $e);
                }
            }

            $status = $response->getStatusCode();

            if ($status === 429) {
                $retryAfter = (float) ($response->getHeaderLine('Retry-After') ?: '60');
                throw new RateLimitException('Rate limit exceeded', 429, $retryAfter);
            }

            if ($status >= 500) {
                $attempt++;
                if ($attempt <= $this->maxRetries) {
                    usleep((int) (self::BASE_DELAY_S * pow(2, $attempt - 1) * 1_000_000));
                    continue;
                }
                $responseBody = $response->getBody()->getContents();
                throw new ServerException(
                    "Server error after {$this->maxRetries} retries: {$responseBody}",
                    $status,
                );
            }

            if ($status >= 200 && $status < 300) {
                return $response;
            }

            $this->handleError($response);
        }
    }

    private function handleError(ResponseInterface $response): never
    {
        $status = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        if ($status === 400 || $status === 422) {
            $fieldErrors = [];
            try {
                $payload = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
                $fieldErrors = $payload['violations'] ?? $payload['errors'] ?? [];
            } catch (\JsonException) {
            }
            throw new ValidationException("Validation error: {$body}", $status, $fieldErrors);
        }

        if ($status === 401) {
            throw new UnauthorizedException();
        }

        if ($status === 403) {
            throw new ForbiddenException();
        }

        if ($status === 404) {
            throw new NotFoundException("Resource not found: {$body}");
        }

        throw new ServerException("Unexpected HTTP error {$status}: {$body}", $status);
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeJson(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();
        if ($response->getStatusCode() === 204 || $body === '') {
            return [];
        }
        try {
            $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            return is_array($decoded) ? $decoded : [];
        } catch (\JsonException) {
            return [];
        }
    }
}
