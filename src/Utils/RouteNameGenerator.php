<?php

namespace Tournikoti\CrudBundle\Utils;

final class RouteNameGenerator
{
    public static function generate(string $type, string $name): string
    {
        return sprintf('admin_%s_%s', $type, $name);
    }
}