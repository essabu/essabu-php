<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class StatisticsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function get(array $params = []): array
    {
        return $this->http->get('/statistics', $params);
    }
}
