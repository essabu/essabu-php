<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\CrudApi;

final class PaymentIntentsApi extends CrudApi
{
    protected string $basePath = '/payment-intents';
}
