<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\CrudApi;

final class ContractsApi extends CrudApi
{
    protected string $basePath = '/contracts';
}
