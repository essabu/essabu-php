<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\CrudApi;

final class VehiclesApi extends CrudApi
{
    protected string $basePath = '/vehicles';
}
