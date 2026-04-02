<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\BaseApi;

final class SubscriptionApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'payment/subscriptions';
    }
    /**
     * @return array<string, mixed>
     */
    public function cancel(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/cancel", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function resume(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/resume");
    }

    /**
     * @return array<string, mixed>
     */
    public function changePlan(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/change-plan", $data);
    }

}
