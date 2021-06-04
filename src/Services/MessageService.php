<?php


namespace App\Services;


use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class MessageService
{
    const TYPE_SUCCESS = "success";
    const TYPE_ERROR = "error";

    private FlashBagInterface $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function addSuccess(string $message): void
    {
        $this->flashBag->add(self::TYPE_SUCCESS,$message);
    }

    public function addError(string $message): void
    {
        $this->flashBag->add(self::TYPE_ERROR, $message);
    }
}