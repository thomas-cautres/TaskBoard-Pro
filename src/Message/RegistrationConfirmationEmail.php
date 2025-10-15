<?php

namespace App\Message;

readonly class RegistrationConfirmationEmail
{
    public function __construct(private string $to, private string $confirmationCode)
    {
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getConfirmationCode(): string
    {
        return $this->confirmationCode;
    }
}
