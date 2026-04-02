<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class LeaveApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/leaves';
    }
    /**
     * @return array<string, mixed>
     */
    public function approve(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/approve");
    }

    /**
     * @return array<string, mixed>
     */
    public function reject(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/reject", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function getBalance(string $employeeId): array
    {
        return $this->httpClient->get("hr/employees/" . $employeeId . "/leave-balance");
    }

}
