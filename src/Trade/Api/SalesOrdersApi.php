<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\CrudApi;

final class SalesOrdersApi extends CrudApi
{
    protected string $basePath = '/sales-orders';
}
