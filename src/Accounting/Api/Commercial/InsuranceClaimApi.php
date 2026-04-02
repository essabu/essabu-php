<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Commercial;
use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class InsuranceClaimApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/insurance-claims';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $claimId): array { return $this->http->get(self::BASE_PATH . "/{$claimId}"); }
    /** @return array<string, mixed> */ public function list(string $contractId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?contractId={$contractId}", $page)); }
    /** @return array<string, mixed> */ public function update(string $claimId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$claimId}", $request); }
    public function delete(string $claimId): void { $this->http->delete(self::BASE_PATH . "/{$claimId}"); }
}
