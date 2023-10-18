<?php

namespace Sherlockode\CrudBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ResourceControllerEvent extends Event
{
    /**
     * @var mixed|null
     */
    private $subject = null;

    /**
     * @var mixed|null
     */
    private $data = null;

    /**
     * @param mixed|null $subject
     */
    public function __construct($subject = null)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed|null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed|null $subject
     *
     * @return $this
     */
    public function setSubject($subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }
}
