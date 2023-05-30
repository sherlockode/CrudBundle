<?php

namespace Sherlockode\CrudBundle\Form\Type;

class FormTypeRegistry
{
    /**
     * @var array
     */
    private $formTypes = [];

    /**
     * @param string $identifier
     * @param string $formType
     *
     * @return void
     */
    public function add(string $identifier, string $formType): void
    {
        $this->formTypes[$identifier] = $formType;
    }

    /**
     * @param string $identifier
     *
     * @return string|null
     */
    public function get(string $identifier): ?string
    {
        return $this->formTypes[$identifier] ?? null;
    }
}
