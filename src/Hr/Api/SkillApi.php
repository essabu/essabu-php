<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class SkillApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/skills';
    }
}
