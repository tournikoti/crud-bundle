<?php

namespace Tournikoti\CrudBundle\Configuration;

use Tournikoti\CrudBundle\Configuration\Model\Field;

interface ConfigurationListInterface
{
    public function add(Field $field): self;

    public function getFields(): array;

    public function getSearchableFields(): array;
}