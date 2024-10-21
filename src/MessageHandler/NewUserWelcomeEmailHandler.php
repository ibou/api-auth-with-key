<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\NewUserWelcomeEmail;

#[AsMessageHandler]
class NewUserWelcomeEmailHandler
{
    public function __construct(
    ) {
    }

    public function __invoke(NewUserWelcomeEmail $welcomeEmail): void
    {
        //$user = $this->userRepository->find($welcomeEmail->getUserId());

    }
}