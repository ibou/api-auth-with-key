<?php

declare(strict_types=1);

namespace App\Message;

class NewUserWelcomeEmail
{
    public function __construct(
        private int $userId,
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}