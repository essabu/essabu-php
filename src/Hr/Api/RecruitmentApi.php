<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class RecruitmentApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/recruitments';
    }
    /**
     * @return array<string, mixed>
     */
    public function shortlist(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/shortlist");
    }

    /**
     * @return array<string, mixed>
     */
    public function hire(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/hire", $data);
    }

}
