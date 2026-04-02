<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class VerificationApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'e-invoice/verifications';
    }
    /**
     * @return array<string, mixed>
     */
    public function verify(array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/verify", $data);
    }

}
