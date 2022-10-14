<?php

namespace Tournikoti\CrudBundle\Manipulator\Model;

class Section
{
    private ?string $value = null;

    public function __construct(
        private readonly string $name,
        private readonly int    $space,
        private readonly int    $indentation = 4
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSpace(): int
    {
        return $this->space;
    }

    public function getIndentation(): int
    {
        return $this->indentation;
    }

    public function toString(): string
    {
        if (null === $this->value) {
            $this->value = sprintf('%s%s:', $this->getSpacePrefix(), $this->getName());
        }

        return $this->value;
    }

    protected function getSpacePrefix(): string
    {
        $prefix = "";

        for ($i = 0; $i < $this->space; $i++) {
            $prefix .= ' ';
        }

        return $prefix;
    }
}