<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\BaseApi;

final class LoanProductApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'payment/loan-products';
    }
}
