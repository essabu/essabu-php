<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Inventory;
use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class WebhookApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/webhooks';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function list(?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH, $page)); }
    public function delete(string $id): void { $this->http->delete(self::BASE_PATH . "/{$id}"); }
}
