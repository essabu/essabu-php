<?php
declare(strict_types=1);
namespace Essabu\Identity\Api;
use Essabu\Common\CrudApi;

final class TenantsApi extends CrudApi
{
    protected string $basePath = '/tenants';
}
