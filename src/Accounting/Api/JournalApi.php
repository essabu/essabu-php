<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class JournalApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/journals';
    }
    /**
     * @return array<string, mixed>
     */
    public function post(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/post");
    }

    /**
     * @return array<string, mixed>
     */
    public function reverse(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/reverse");
    }

}
