<?php

namespace Tournikoti\CrudBundle\Configuration\Model\Type;

use Tournikoti\CrudBundle\Configuration\Model\Field;

class EnumField extends Field
{
    const DEFAULT_VIEW = '@TournikotiCrud/CRUD/fields/enum.html.twig';

    public function __construct(string $property, string $label, ?string $view = null)
    {
        parent::__construct($property, $label, $view ?? self::DEFAULT_VIEW);
    }
}