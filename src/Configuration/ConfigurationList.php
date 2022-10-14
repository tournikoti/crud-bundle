<?php

namespace Tournikoti\CrudBundle\Configuration;

use Tournikoti\CrudBundle\Configuration\Model\Field;
use Tournikoti\CrudBundle\Configuration\Model\SearchableField;

class ConfigurationList implements ConfigurationListInterface
{
    /**
     * @var Field[]
     */
    private array $fields = [];

    public function add(Field $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function getSearchableFields(): array
    {
        return array_filter($this->fields, function (Field $field) {
            return $field instanceof SearchableField;
        });
    }
}