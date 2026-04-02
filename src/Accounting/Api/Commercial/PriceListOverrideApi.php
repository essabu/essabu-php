<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Commercial;
use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class PriceListOverrideApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/price-list-overrides';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $overrideId): array { return $this->http->get(self::BASE_PATH . "/{$overrideId}"); }
    /** @return array<string, mixed> */ public function list(string $priceListId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?priceListId={$priceListId}", $page)); }
    /** @return array<string, mixed> */ public function update(string $overrideId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$overrideId}", $request); }
    public function delete(string $overrideId): void { $this->http->delete(self::BASE_PATH . "/{$overrideId}"); }
}
