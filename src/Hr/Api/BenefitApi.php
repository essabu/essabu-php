<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class BenefitApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/benefits';
    }
    /**
     * @return array<string, mixed>
     */
    public function assign(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/assign", $data);
    }

}
