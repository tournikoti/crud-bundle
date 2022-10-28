<?php

namespace Tournikoti\CrudBundle\Asset;

interface AssetManagerInterface
{
    public function getCss(): array;

    public function getJs(): array;
}