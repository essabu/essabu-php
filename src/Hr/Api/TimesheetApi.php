<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class TimesheetApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/timesheets';
    }
    /**
     * @return array<string, mixed>
     */
    public function submit(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/submit");
    }

    /**
     * @return array<string, mixed>
     */
    public function approve(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/approve");
    }

}
