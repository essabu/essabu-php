<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class CreditNoteApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/credit-notes';
    }
    /**
     * @return array<string, mixed>
     */
    public function apply(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/apply", $data);
    }

}
