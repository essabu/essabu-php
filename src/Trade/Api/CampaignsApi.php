<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\CrudApi;

final class CampaignsApi extends CrudApi
{
    protected string $basePath = '/campaigns';
}
