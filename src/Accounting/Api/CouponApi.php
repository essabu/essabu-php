<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class CouponApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/coupons';
    }
    /**
     * @return array<string, mixed>
     */
    public function validate(string $code): array
    {
        return $this->httpClient->post($this->basePath() . "/validate", ["code" => $code]);
    }

}
