<?php

namespace Tournikoti\CrudBundle\Template;

interface TemplateRegistryInterface
{
    public function getTemplate(string $name): Template;
}