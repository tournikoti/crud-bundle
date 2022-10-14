<?php

namespace Tournikoti\CrudBundle\Route\Exception;

class UndefinedRouteException extends \LogicException
{
    public function __construct(string $name, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf("The route name %s does not exist", $name), $code, $previous);
    }
}