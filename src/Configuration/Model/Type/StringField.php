<?php

namespace Tournikoti\CrudBundle\Configuration\Model\Type;

use Tournikoti\CrudBundle\Configuration\Model\Field;
use Tournikoti\CrudBundle\Configuration\Model\SearchableField;

class StringField extends Field implements SearchableField
{
    const DEFAULT_VIEW = '@TournikotiCrud/CRUD/fields/string.html.twig';

    public function __construct(string $property, string $label, ?string $view = null)
    {
        parent::__construct($property, $label, $view ?? self::DEFAULT_VIEW);
    }
}