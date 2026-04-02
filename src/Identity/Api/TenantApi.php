<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

final class TenantApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'identity/tenants';
    }
}
