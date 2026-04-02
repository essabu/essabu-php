<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class SubmissionApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'e-invoice/submissions';
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
    public function getStatus(string $id): array
    {
        return $this->httpClient->get($this->basePath() . "/" . $id . "/status");
    }

}
