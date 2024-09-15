<?php

namespace App\Services\Sms;

class ArraySender implements SmsSender
{
    private $messages = [];

    public function send($number, $text): void
    {
        $this->messages[] = [
            'to' => '+' . trim($number, '+'),
            'text' => $text,
        ];
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
