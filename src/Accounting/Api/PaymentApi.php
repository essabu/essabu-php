<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class PaymentApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/payments';
    }
    /**
     * @return array<string, mixed>
     */
    public function reconcile(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/reconcile", $data);
    }

}
