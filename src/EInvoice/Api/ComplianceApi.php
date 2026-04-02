<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class ComplianceApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'e-invoice/compliance';
    }
    /**
     * @return array<string, mixed>
     */
    public function check(array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/check", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function getRules(string $country): array
    {
        return $this->httpClient->get($this->basePath() . "/rules/" . $country);
    }

}
