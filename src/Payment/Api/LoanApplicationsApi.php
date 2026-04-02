<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\CrudApi;

final class LoanApplicationsApi extends CrudApi
{
    protected string $basePath = '/loan-applications';
}
