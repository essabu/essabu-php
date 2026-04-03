<?php

declare(strict_types=1);

namespace Essabu\Compliance;

use Essabu\Common\HttpClient;
use Essabu\Compliance\Api\AuditApi;
use Essabu\Compliance\Api\IncidentApi;
use Essabu\Compliance\Api\PolicyApi;

/**
 * @property-read AuditApi $audits
 * @property-read PolicyApi $policies
 * @property-read IncidentApi $incidents
 */
final class ComplianceClient
{
    /** @var array<string, object> */
    private array $instances = [];

    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function __get(string $name): object
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $this->instances[$name] = match ($name) {
            'audits' => new AuditApi($this->httpClient),
            'policies' => new PolicyApi($this->httpClient),
            'incidents' => new IncidentApi($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown Compliance API: {$name}"),
        };

        return $this->instances[$name];
    }
}
