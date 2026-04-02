<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class EmployeeApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/employees';
    }
    /**
     * @return array<string, mixed>
     */
    public function terminate(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/terminate", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function reinstate(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/reinstate");
    }

    /**
     * @return array<string, mixed>
     */
    public function getDocuments(string $id): array
    {
        return $this->httpClient->get($this->basePath() . "/" . $id . "/documents");
    }

}
