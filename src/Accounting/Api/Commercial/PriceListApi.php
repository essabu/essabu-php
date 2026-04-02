<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Commercial;
use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class PriceListApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/price-lists';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $priceListId): array { return $this->http->get(self::BASE_PATH . "/{$priceListId}"); }
    /** @return array<string, mixed> */ public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */ public function update(string $priceListId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$priceListId}", $request); }
    public function delete(string $priceListId): void { $this->http->delete(self::BASE_PATH . "/{$priceListId}"); }
}
