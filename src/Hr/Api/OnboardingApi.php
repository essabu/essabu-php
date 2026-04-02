<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class OnboardingApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/onboardings';
    }
    /**
     * @return array<string, mixed>
     */
    public function start(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/start");
    }

    /**
     * @return array<string, mixed>
     */
    public function completeStep(string $id, string $stepId): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/steps/" . $stepId . "/complete");
    }

}
