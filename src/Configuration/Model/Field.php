<?php

namespace Tournikoti\CrudBundle\Configuration\Model;

class Field implements FieldInterface
{
    public function __construct(
        private string $property,
        private string $label,
        private string $view,
        private array  $parameters = [],
    )
    {
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    public function getParameter(string $name): mixed
    {
        return $this->hasParameter($name) ? $this->parameters[$name] : null;
    }
}