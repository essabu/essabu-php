<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api\Core;

use Essabu\Common\BaseApi;

final class ConfigApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/config';

    /** @return array<string, mixed> */
    public function get(string $companyId): array { return $this->http->get(self::BASE_PATH . "?companyId={$companyId}"); }
    /** @return array<string, mixed> */
    public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */
    public function update(string $configId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$configId}", $request); }
}
