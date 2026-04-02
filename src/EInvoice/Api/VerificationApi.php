<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class VerificationApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function verify(string $invoiceId): array
    {
        return $this->http->post('/verification/verify', ['invoice_id' => $invoiceId]);
    }
}
