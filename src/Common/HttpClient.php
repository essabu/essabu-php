<?php

declare(strict_types=1);

namespace Essabu\Common;

use Essabu\Common\Exception\AuthenticationException;
use Essabu\Common\Exception\AuthorizationException;
use Essabu\Common\Exception\BadRequestException;
use Essabu\Common\Exception\ConflictException;
use Essabu\Common\Exception\EssabuException;
use Essabu\Common\Exception\NotFoundException;
use Essabu\Common\Exception\RateLimitException;
use Essabu\Common\Exception\ServerException;
use Essabu\Common\Exception\ValidationException;
use Essabu\EssabuConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

final class HttpClient
{
    private Client $client;
    private AuthInterceptor $authInterceptor;

    public function __construct(
        private readonly EssabuConfig $config,
        ?Client $client = null,
    ) {
        $this->authInterceptor = new AuthInterceptor($config);

        if ($client !== null) {
            $this->client = $client;
            return;
        }

        $stack = HandlerStack::create();
        $stack->push($this->retryMiddleware());

        $this->client = new Client([
            'handler' => $stack,
            'timeout' => $config->timeout,
            'connect_timeout' => $config->connectTimeout,
            'http_errors' => true,
        ]);
    }

    /**
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     */
    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, ['query' => $query]);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function post(string $path, array $data = []): array
    {
        return $this->request('POST', $path, ['json' => $data]);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function put(string $path, array $data = []): array
    {
        return $this->request('PUT', $path, ['json' => $data]);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function patch(string $path, array $data = []): array
    {
        return $this->request('PATCH', $path, [
            'json' => $data,
            'headers' => ['Content-Type' => 'application/merge-patch+json'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $path): array
    {
        return $this->request('DELETE', $path);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function upload(string $path, array $data): array
    {
        $multipart = [];
        /** @var resource[] $openedResources */
        $openedResources = [];

        foreach ($data as $key => $value) {
            if (is_resource($value) || (is_string($value) && file_exists($value))) {
                if (is_string($value)) {
                    $resource = fopen($value, 'r');
                    if ($resource === false) {
                        throw new EssabuException("Failed to open file: {$value}");
                    }
                    $openedResources[] = $resource;
                    $contents = $resource;
                } else {
                    $contents = $value;
                }
                $multipart[] = [
                    'name' => $key,
                    'contents' => $contents,
                ];
            } else {
                $multipart[] = [
                    'name' => $key,
                    'contents' => is_string($value) ? $value : (string) $value,
                ];
            }
        }

        $url = $this->config->buildUrl($path);
        $headers = $this->authInterceptor->getHeaders();
        unset($headers['Content-Type']);

        try {
            $response = $this->client->request('POST', $url, [
                'headers' => $headers,
                'multipart' => $multipart,
            ]);

            return $this->decodeResponse($response);
        } catch (ClientException | GuzzleServerException $e) {
            throw $this->mapException($e);
        } finally {
            foreach ($openedResources as $res) {
                if (is_resource($res)) {
                    fclose($res);
                }
            }
        }
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function request(string $method, string $path, array $options = []): array
    {
        $url = $this->config->buildUrl($path);
        $headers = $this->authInterceptor->getHeaders();

        if (isset($options['headers'])) {
            /** @var array<string, string> $optHeaders */
            $optHeaders = $options['headers'];
            $headers = array_merge($headers, $optHeaders);
            unset($options['headers']);
        }

        $options['headers'] = $headers;

        try {
            $response = $this->client->request($method, $url, $options);
            return $this->decodeResponse($response);
        } catch (ClientException | GuzzleServerException $e) {
            throw $this->mapException($e);
        } catch (ConnectException $e) {
            throw new EssabuException(
                'Connection failed: ' . $e->getMessage(),
                0,
                $e,
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeResponse(Response|\Psr\Http\Message\ResponseInterface $response): array
    {
        $body = (string) $response->getBody();

        if ($body === '' || $response->getStatusCode() === 204) {
            return [];
        }

        /** @var array<string, mixed>|null $decoded */
        $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        return $decoded ?? [];
    }

    private function mapException(ClientException|GuzzleServerException $e): EssabuException
    {
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();

        /** @var array<string, mixed> $data */
        $data = json_decode($body, true) ?? [];
        $message = (string) ($data['message'] ?? $data['detail'] ?? $data['title'] ?? $e->getMessage());

        return match (true) {
            $statusCode === 400 => new BadRequestException($message, $data),
            $statusCode === 401 => new AuthenticationException($message, $data),
            $statusCode === 403 => new AuthorizationException($message, $data),
            $statusCode === 404 => new NotFoundException($message, $data),
            $statusCode === 409 => new ConflictException($message, $data),
            $statusCode === 422 => new ValidationException(
                $message,
                isset($data['violations']) && is_array($data['violations']) ? $data['violations'] : [],
                $data,
            ),
            $statusCode === 429 => new RateLimitException(
                $message,
                $response->hasHeader('Retry-After')
                    ? (int) $response->getHeaderLine('Retry-After')
                    : null,
                $data,
            ),
            $statusCode >= 500 => new ServerException($message, $statusCode, $data),
            default => new EssabuException($message, $statusCode, $e, $data),
        };
    }

    private function retryMiddleware(): callable
    {
        return Middleware::retry(
            function (int $retries, Request $request, ?Response $response, ?\Throwable $exception): bool {
                if ($retries >= $this->config->retries) {
                    return false;
                }

                if ($exception instanceof ConnectException) {
                    return true;
                }

                if ($response !== null) {
                    $status = $response->getStatusCode();
                    return $status === 429 || $status >= 500;
                }

                return false;
            },
            function (int $retries): int {
                return (int) (1000 * pow(2, $retries));
            },
        );
    }

    public function getGuzzleClient(): Client
    {
        return $this->client;
    }
}
