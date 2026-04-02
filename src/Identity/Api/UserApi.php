<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

final class UserApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'identity/users';
    }
    /**
     * @return array<string, mixed>
     */
    public function assignRole(string $id, string $roleId): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/roles", ["roleId" => $roleId]);
    }

    /**
     * @return array<string, mixed>
     */
    public function removeRole(string $id, string $roleId): array
    {
        return $this->httpClient->delete($this->basePath() . "/" . $id . "/roles/" . $roleId);
    }

    /**
     * @return array<string, mixed>
     */
    public function activate(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/activate");
    }

    /**
     * @return array<string, mixed>
     */
    public function deactivate(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/deactivate");
    }

}
