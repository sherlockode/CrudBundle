<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\View;

use Sherlockode\CrudBundle\Field\FieldInterface;

class Field implements FieldInterface
{
    private ?string $key = null;

    private ?string $label = null;

    private ?string $path = null;

    private ?string $template = null;

    private ?array $options = null;

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return $this
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path ?? $this->getKey();
    }

    /**
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @return $this
     */
    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
