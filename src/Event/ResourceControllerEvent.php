<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ResourceControllerEvent extends Event
{
    public const BEFORE_CREATE = 'sherlockode_crud.before_create';

    public const BEFORE_UPDATE = 'sherlockode_crud.before_update';

    public const BEFORE_DELETE = 'sherlockode_crud.before_delete';

    private bool $cancelProcess = false;

    private ?string $message = null;

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

    public function isCancelProcess(): bool
    {
        return $this->cancelProcess;
    }

    /**
     * @return $this
     */
    public function setCancelProcess(bool $cancelProcess): self
    {
        $this->cancelProcess = $cancelProcess;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return $this
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
