<?php

namespace Tournikoti\CrudBundle\Manipulator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Tournikoti\CrudBundle\Manipulator\Model\Section;

abstract class Manipulator implements ManipulatorInterface
{
    protected array $data;

    protected Collection $sections;

    protected int $space = 0;

    protected int $startLine;

    protected ?int $endLine;

    public function load(string $data): static
    {
        $this->sections = new ArrayCollection();
        $this->data = preg_split('/\R/', $data);

        return $this;
    }

    public function get(): string
    {
        return implode(PHP_EOL, $this->data);
    }

    protected function addSection(string $name, int $space, int $indentation = 4): self
    {
        $this->sections->add(new Section($name, $space, $indentation));

        return $this;
    }

    protected function getSection(): Section
    {
        return !$this->sections->isEmpty()
            ? $this->sections->last()
            : new Section('root', 0, 0);
    }

    protected function getSpacePrefix(): string
    {
        return str_pad('', $this->getSection()->getSpace() + $this->getSection()->getIndentation(), ' ');
    }

    protected function getSpaceIndentationOfRow(int $num): ?int
    {
        while (array_key_exists($num, $this->data)) {
            if (preg_match('/^(\s*).+$/', $this->data[$num], $matches)) {
                if (strlen($matches[1]) > $this->space) {
                    return strlen($matches[1]);
                }
            }

            $num++;
        }

        return null;
    }
}