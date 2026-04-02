<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\BaseApi;

final class CollateralApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'payment/collaterals';
    }
}
