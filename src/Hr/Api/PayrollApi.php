<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class PayrollApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/payrolls';
    }
    /**
     * @return array<string, mixed>
     */
    public function calculate(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/calculate");
    }

    /**
     * @return array<string, mixed>
     */
    public function approve(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/approve");
    }

    /**
     * @return array<string, mixed>
     */
    public function generatePayslips(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/payslips");
    }

}
