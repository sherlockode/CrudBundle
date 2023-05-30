<?php

namespace Sherlockode\CrudBundle\Grid;

class Action
{
    /**
     * @var string
     */
    private $template;

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }
}
