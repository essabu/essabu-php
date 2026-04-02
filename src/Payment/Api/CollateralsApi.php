<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\CrudApi;

final class CollateralsApi extends CrudApi
{
    protected string $basePath = '/collaterals';
}
