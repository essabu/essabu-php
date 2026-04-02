<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class SubmissionsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function submit(string $invoiceId, ?array $metadata = null): array
    {
        $data = ['invoice_id' => $invoiceId];
        if ($metadata !== null) {
            $data['metadata'] = $metadata;
        }
        return $this->http->post('/submissions', $data);
    }

    /** @return array<string, mixed> */
    public function checkStatus(string $submissionId): array
    {
        return $this->http->get("/submissions/{$submissionId}/status");
    }
}
