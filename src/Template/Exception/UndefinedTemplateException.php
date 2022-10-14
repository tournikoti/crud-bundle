<?php

namespace Tournikoti\CrudBundle\Template\Exception;

class UndefinedTemplateException extends \LogicException
{
    public function __construct(string $name, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf("The template name %s does not exist", $name), $code, $previous);
    }
}