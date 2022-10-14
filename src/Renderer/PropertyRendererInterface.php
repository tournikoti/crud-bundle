<?php

namespace Tournikoti\CrudBundle\Renderer;

use Tournikoti\CrudBundle\Configuration\Model\Field;

interface PropertyRendererInterface
{
    public function render(object $entity, Field $property): mixed;
}