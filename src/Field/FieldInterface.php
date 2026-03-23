<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Field;

interface FieldInterface
{
    public function getKey(): string;

    /**
     * @return $this
     */
    public function setKey(string $key): self;

    public function getLabel(): string;

    /**
     * @return $this
     */
    public function setLabel(string $label): self;

    public function getPath(): string;

    /**
     * @return $this
     */
    public function setPath(string $path): self;

    public function getTemplate(): ?string;

    /**
     * @return $this
     */
    public function setTemplate(?string $template): self;

    public function getOptions(): array;

    /**
     * @return $this
     */
    public function setOptions(array $options): self;
}
