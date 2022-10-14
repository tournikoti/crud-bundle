<?php

namespace Tournikoti\CrudBundle\Utils;

final class RoleNameGenerator
{
    public static function generate(string $type, string $name): string
    {
        return sprintf('ROLE_ADMIN_%s_%s', strtoupper($type), strtoupper($name));
    }
}