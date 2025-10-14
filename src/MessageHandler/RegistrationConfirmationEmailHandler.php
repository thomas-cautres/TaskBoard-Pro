<?php

namespace App\MessageHandler;

use App\Message\RegistrationConfirmationEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegistrationConfirmationEmailHandler
{
    public function __invoke(RegistrationConfirmationEmail $message): void
    {
        // TODO
    }
}
