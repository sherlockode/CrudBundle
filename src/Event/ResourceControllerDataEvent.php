<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ResourceControllerDataEvent extends Event
{
    public const SHOW = 'sherlockode_crud.show';

    public const CREATE = 'sherlockode_crud.create';

    public const UPDATE = 'sherlockode_crud.update';

    public const DELETE_CONFIRMATION = 'sherlockode_crud.delete_confirmation';

    /**
     * @var mixed|null
     */
    private $data;

    /**
     * @param mixed|null $subject
     */
    public function __construct(private $subject = null)
    {
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
