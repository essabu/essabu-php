<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Inventory;
use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class StockCountApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/stock-counts';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $id): array { return $this->http->get(self::BASE_PATH . "/{$id}"); }
    /** @return array<string, mixed> */ public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */ public function update(string $id, array $request): array { return $this->http->put(self::BASE_PATH . "/{$id}", $request); }
    public function delete(string $id): void { $this->http->delete(self::BASE_PATH . "/{$id}"); }
}
