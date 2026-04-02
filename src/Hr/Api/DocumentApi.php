<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class DocumentApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/documents';
    }
    /**
     * @return array<string, mixed>
     */
    public function upload(string $employeeId, array $data): array
    {
        return $this->httpClient->upload("hr/employees/" . $employeeId . "/documents", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function download(string $id): array
    {
        return $this->httpClient->get($this->basePath() . "/" . $id . "/download");
    }

}
