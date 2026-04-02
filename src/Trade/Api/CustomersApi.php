<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\CrudApi;

final class CustomersApi extends CrudApi
{
    protected string $basePath = '/customers';
}
