<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class SupplierApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/suppliers';
    }
}
