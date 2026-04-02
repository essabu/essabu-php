<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class InvoicesApi extends BaseApi
{
    /** @param array<string, mixed> $data @return array<string, mixed> */
    public function normalize(array $data): array
    {
        return $this->http->post('/invoices/normalize', $data);
    }
}
