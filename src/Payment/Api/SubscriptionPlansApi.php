<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\CrudApi;

final class SubscriptionPlansApi extends CrudApi
{
    protected string $basePath = '/subscription-plans';
}
