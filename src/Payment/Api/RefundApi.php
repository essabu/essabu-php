<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\BaseApi;

final class RefundApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'payment/refunds';
    }
}
