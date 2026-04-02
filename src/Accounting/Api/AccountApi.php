<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class AccountApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/accounts';
    }
}
