<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Grid;

class Action
{
    private ?string $template = null;

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
}
