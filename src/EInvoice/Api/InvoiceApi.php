<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class InvoiceApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'e-invoice/invoices';
    }
    /**
     * @return array<string, mixed>
     */
    public function sign(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/sign");
    }

    /**
     * @return array<string, mixed>
     */
    public function downloadXml(string $id): array
    {
        return $this->httpClient->get($this->basePath() . "/" . $id . "/xml");
    }

}
