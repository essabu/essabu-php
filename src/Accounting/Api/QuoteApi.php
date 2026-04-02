<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class QuoteApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/quotes';
    }
    /**
     * @return array<string, mixed>
     */
    public function accept(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/accept");
    }

    /**
     * @return array<string, mixed>
     */
    public function reject(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/reject");
    }

    /**
     * @return array<string, mixed>
     */
    public function convertToInvoice(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/convert-to-invoice");
    }

}
