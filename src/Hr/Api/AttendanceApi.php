<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class AttendanceApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/attendances';
    }
    /**
     * @return array<string, mixed>
     */
    public function clockIn(array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/clock-in", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function clockOut(array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/clock-out", $data);
    }

}
