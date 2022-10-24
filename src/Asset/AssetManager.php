<?php

namespace Tournikoti\CrudBundle\Asset;

class AssetManager implements AssetManagerInterface
{
    public function __construct(private array $assets)
    {
    }

    public function getCss(): array
    {
        return $this->assets['css'] ?? [];
    }

    public function getJs(): array
    {
        return $this->assets['js'] ?? [];
    }

    public function get(string $key): array
    {
        return $this->assets[$key] ?? [];
    }
}