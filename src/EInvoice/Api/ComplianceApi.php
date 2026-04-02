<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class ComplianceApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function generateReport(array $params): array
    {
        return $this->http->post('/compliance/reports', $params);
    }
}
