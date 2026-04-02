<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class DepartmentApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/departments';
    }
}
