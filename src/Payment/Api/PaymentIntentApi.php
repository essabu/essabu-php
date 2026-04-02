<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\BaseApi;

final class PaymentIntentApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'payment/payment-intents';
    }
    /**
     * @return array<string, mixed>
     */
    public function confirm(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/confirm");
    }

    /**
     * @return array<string, mixed>
     */
    public function capture(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/capture", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function cancel(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/cancel");
    }

}
