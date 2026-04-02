<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class ExpenseApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/expenses';
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
    public function reimburse(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/reimburse");
    }

}
