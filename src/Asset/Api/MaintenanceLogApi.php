<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\BaseApi;

final class MaintenanceLogApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'asset/maintenance-logs';
    }
    /**
     * @return array<string, mixed>
     */
    public function complete(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/complete", $data);
    }

}
