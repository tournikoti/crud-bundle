<?php

namespace Tournikoti\CrudBundle\Configuration\Model;

interface FieldInterface
{
    public function getProperty(): string;

    public function getLabel(): string;

    public function getView(): ?string;
}