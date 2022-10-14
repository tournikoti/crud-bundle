<?php

namespace Tournikoti\CrudBundle\Configuration\Model\Type;

use Tournikoti\CrudBundle\Configuration\Model\Field;

class DateTimeField extends Field
{
    const DEFAULT_VIEW = '@TournikotiCrud/CRUD/fields/datetime.html.twig';

    public function __construct(string $property, string $label, string $format = 'd/m/Y H:i:s', ?string $view = null)
    {
        parent::__construct($property, $label, $view ?? self::DEFAULT_VIEW, [
            'format' => $format,
        ]);
    }
}