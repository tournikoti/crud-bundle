<?php

namespace Tournikoti\CrudBundle\Manipulator;

interface ManipulatorInterface
{
    public function load(string $data): static;

    public function section(string $name): static;

    public function add(string $name): static;

    public function setValue(string $name, mixed $value): static;

    public function end(): static;

    public function get(): string;
}