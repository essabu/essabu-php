<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class FiscalYearApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/fiscal-years';
    }
    /**
     * @return array<string, mixed>
     */
    public function close(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/close");
    }

}
