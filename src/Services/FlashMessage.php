<?php

namespace App\Services;

use Slim\Flash\Messages;

class FlashMessage
{
    private $flash;
    
    public function __construct(Messages $flash)
    {
        $this->flash = $flash;
    }
    
    public function addMessage(string $key, string $message): void
    {
        $this->flash->addMessage($key, $message);
    }
    
    public function getMessages(): array
    {
        return $this->flash->getMessages();
    }
}