<?php
declare(strict_types=1);
namespace Essabu\Identity\Api;
use Essabu\Common\CrudApi;

final class UsersApi extends CrudApi
{
    protected string $basePath = '/users';
}
