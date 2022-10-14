<?php

namespace Tournikoti\CrudBundle\Manipulator\File;

use Symfony\Component\Yaml\Yaml;
use Tournikoti\CrudBundle\Manipulator\Manipulator;

class YamlFileManipulator extends Manipulator
{
    public function section(string $name): static
    {
        foreach ($this->data as $num => $row) {
            if (preg_match(sprintf('/^(\s{%d})%s:$/', $this->getSection()->getSpace(), $name), $row, $matches)) {
                $this->addSection($name, strlen($matches[1]), $this->getSpaceIndentationOfRow($num));
                return $this;
            }
        }

        $this->add($name);

        return $this;
    }

    public function add(string $name): static
    {
        array_splice($this->data, $this->getEndLine(), 0, $this->getSpacePrefix() . $name . ':');

        $this->addSection($name, $this->getSection()->getSpace() + $this->getSection()->getIndentation());

        return $this;
    }

    public function setValue(string $name, mixed $value): static
    {
        array_splice($this->data, $this->getEndLine(), 0, sprintf('%s: %s', $this->getSpacePrefix() . $name, $this->dump($value)));

        return $this;
    }

    public function end(): static
    {
        $this->sections->removeElement($this->sections->last());

        return $this;
    }

    protected function getStartLine(): int
    {
        foreach ($this->data as $key => $row) {
            if ($row === $this->getSection()->toString()) {
                return $key;
            }
        }

        return count($this->data);
    }

    protected function getEndLine(): int
    {
        $startLine = $this->getStartLine();
        $count = 0;

        foreach ($this->data as $key => $row) {
            if ($key > $startLine) {
                preg_match(sprintf('/^(\s*)[^ ]{1}.*:$/', $this->getSection()->getSpace()), $row, $matches);

                if (isset($matches[1]) && strlen($matches[1]) <= $this->getSection()->getSpace()) {
                    return $startLine + $count;
                }

                $count++;
            }
        }

        return count($this->data);
    }

    protected function dump($value)
    {
        if (is_array($value)) {
            return Yaml::dump($value, 0);
        }

        return Yaml::dump($value);
    }
}