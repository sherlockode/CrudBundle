<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Grid;

class Filter
{
    private ?string $name = null;

    private ?string $label = null;

    private ?string $type = null;

    private ?string $filterType = null;

    private ?string $template = null;

    private ?array $options = null;

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFilterType(): string
    {
        return $this->filterType;
    }

    /**
     * @return $this
     */
    public function setFilterType(string $filterType): self
    {
        $this->filterType = $filterType;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return $this
     */
    public function setTemplate(string $template): self
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
