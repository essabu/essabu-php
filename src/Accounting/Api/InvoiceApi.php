<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class InvoiceApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/invoices';
    }
    /**
     * @return array<string, mixed>
     */
    public function finalize(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/finalize");
    }

    /**
     * @return array<string, mixed>
     */
    public function send(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/send");
    }

    /**
     * @return array<string, mixed>
     */
    public function void(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/void");
    }

    /**
     * @return array<string, mixed>
     */
    public function markAsPaid(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/mark-paid", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function downloadPdf(string $id): array
    {
        return $this->httpClient->get($this->basePath() . "/" . $id . "/pdf");
    }

}
