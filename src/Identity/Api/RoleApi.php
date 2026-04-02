<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

final class RoleApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'identity/roles';
    }
    /**
     * @return array<string, mixed>
     */
    public function assignPermissions(string $id, array $permissionIds): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/permissions", ["permissions" => $permissionIds]);
    }

}
