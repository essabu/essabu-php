<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\BaseApi;

final class KycApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'payment/kyc';
    }
    /**
     * @return array<string, mixed>
     */
    public function verify(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/verify");
    }

    /**
     * @return array<string, mixed>
     */
    public function submitDocuments(string $id, array $data): array
    {
        return $this->httpClient->upload($this->basePath() . "/" . $id . "/documents", $data);
    }

}
