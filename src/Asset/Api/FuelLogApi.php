<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\BaseApi;

final class FuelLogApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'asset/fuel-logs';
    }
}
