<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\CrudApi;

final class WarehousesApi extends CrudApi
{
    protected string $basePath = '/warehouses';
}
