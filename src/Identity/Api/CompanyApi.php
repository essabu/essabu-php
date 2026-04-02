<?php

declare(strict_types=1);

namespace Essabu\Identity\Api;

use Essabu\Common\BaseApi;

final class CompanyApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'identity/companies';
    }
}
