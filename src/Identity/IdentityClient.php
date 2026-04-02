<?php

declare(strict_types=1);

namespace Essabu\Identity;

use Essabu\Common\HttpClient;
use Essabu\Identity\Api\AuthApi;
use Essabu\Identity\Api\UsersApi;
use Essabu\Identity\Api\ProfilesApi;
use Essabu\Identity\Api\CompaniesApi;
use Essabu\Identity\Api\TenantsApi;
use Essabu\Identity\Api\RolesApi;
use Essabu\Identity\Api\PermissionsApi;
use Essabu\Identity\Api\BranchesApi;
use Essabu\Identity\Api\ApiKeysApi;
use Essabu\Identity\Api\SessionsApi;

/**
 * Identity module client.
 *
 * Access via: $essabu->identity->auth->login([...])
 *
 * @property-read AuthApi $auth
 * @property-read UsersApi $users
 * @property-read ProfilesApi $profiles
 * @property-read CompaniesApi $companies
 * @property-read TenantsApi $tenants
 * @property-read RolesApi $roles
 * @property-read PermissionsApi $permissions
 * @property-read BranchesApi $branches
 * @property-read ApiKeysApi $apiKeys
 * @property-read SessionsApi $sessions
 */
final class IdentityClient
{
    /** @var array<string, object> */
    private array $cache = [];

    public function __construct(
        private readonly HttpClient $http,
    ) {
    }

    public function __get(string $name): object
    {
        return match ($name) {
            'auth' => $this->resolve($name, AuthApi::class),
            'users' => $this->resolve($name, UsersApi::class),
            'profiles' => $this->resolve($name, ProfilesApi::class),
            'companies' => $this->resolve($name, CompaniesApi::class),
            'tenants' => $this->resolve($name, TenantsApi::class),
            'roles' => $this->resolve($name, RolesApi::class),
            'permissions' => $this->resolve($name, PermissionsApi::class),
            'branches' => $this->resolve($name, BranchesApi::class),
            'apiKeys' => $this->resolve($name, ApiKeysApi::class),
            'sessions' => $this->resolve($name, SessionsApi::class),
            default => throw new \InvalidArgumentException("Unknown Identity API: {$name}"),
        };
    }

    private function resolve(string $name, string $class): object
    {
        return $this->cache[$name] ??= new $class($this->http);
    }
}
