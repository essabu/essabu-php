<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class DeliveryApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/deliveries';
    }
    /**
     * @return array<string, mixed>
     */
    public function ship(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/ship", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function deliver(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/deliver");
    }

}
