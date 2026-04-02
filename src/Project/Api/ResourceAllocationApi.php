<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class ResourceAllocationApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'project/resource-allocations';
    }
}
