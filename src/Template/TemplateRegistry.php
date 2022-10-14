<?php

namespace Tournikoti\CrudBundle\Template;

use Tournikoti\CrudBundle\Template\Exception\UndefinedTemplateException;

class TemplateRegistry implements TemplateRegistryInterface
{
    private array $templates = [];

    public function hasTemplate(string $name): bool
    {
        return isset($this->templates[$name]);
    }

    public function getTemplate(string $name): Template
    {
        if (!$this->hasTemplate($name)) {
            throw new UndefinedTemplateException($name);
        }

        return $this->templates[$name];
    }

    public function setTemplateIndex(Template $view): self
    {
        $this->setTemplate('index', $view);

        return $this;
    }

    public function setTemplateNew(Template $view): self
    {
        $this->setTemplate('new', $view);

        return $this;
    }

    public function setTemplateShow(Template $view): self
    {
        $this->setTemplate('show', $view);

        return $this;
    }

    public function setTemplateEdit(Template $view): self
    {
        $this->setTemplate('edit', $view);

        return $this;
    }

    public function setTemplateDelete(Template $view): self
    {
        $this->setTemplate('delete', $view);

        return $this;
    }

    public function setTemplate(string $name, Template $template): self
    {
        $this->templates[$name] = $template;

        return $this;
    }
}