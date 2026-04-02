<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class ProductApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/products';
    }
}
