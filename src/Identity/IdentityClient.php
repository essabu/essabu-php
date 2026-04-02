<?php

declare(strict_types=1);

namespace Essabu\Identity;

use Essabu\Common\HttpClient;
use Essabu\Identity\Api\ApiKeyApi;
use Essabu\Identity\Api\AuthApi;
use Essabu\Identity\Api\BranchApi;
use Essabu\Identity\Api\CompanyApi;
use Essabu\Identity\Api\PermissionApi;
use Essabu\Identity\Api\ProfileApi;
use Essabu\Identity\Api\RoleApi;
use Essabu\Identity\Api\SessionApi;
use Essabu\Identity\Api\TenantApi;
use Essabu\Identity\Api\UserApi;

/**
 * @property-read AuthApi $auth
 * @property-read UserApi $users
 * @property-read RoleApi $roles
 * @property-read PermissionApi $permissions
 * @property-read TenantApi $tenants
 * @property-read CompanyApi $companies
 * @property-read BranchApi $branches
 * @property-read ProfileApi $profile
 * @property-read SessionApi $sessions
 * @property-read ApiKeyApi $apiKeys
 */
final class IdentityClient
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
            'auth' => new AuthApi($this->httpClient),
            'users' => new UserApi($this->httpClient),
            'roles' => new RoleApi($this->httpClient),
            'permissions' => new PermissionApi($this->httpClient),
            'tenants' => new TenantApi($this->httpClient),
            'companies' => new CompanyApi($this->httpClient),
            'branches' => new BranchApi($this->httpClient),
            'profile' => new ProfileApi($this->httpClient),
            'sessions' => new SessionApi($this->httpClient),
            'apiKeys' => new ApiKeyApi($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown Identity API: {$name}"),
        };

        return $this->instances[$name];
    }
}
