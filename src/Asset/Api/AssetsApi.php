<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\CrudApi;

final class AssetsApi extends CrudApi
{
    protected string $basePath = '/assets';
}
