<?php

namespace Sherlockode\CrudBundle\Field;

interface FieldInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey(string $key): self;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel(string $label): self;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path): self;

    /**
     * @return string|null
     */
    public function getTemplate(): ?string;

    /**
     * @param string|null $template
     *
     * @return $this
     */
    public function setTemplate(?string $template): self;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options): self;
}
