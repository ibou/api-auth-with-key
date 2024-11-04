<?php

declare(strict_types=1);

namespace App\Event;

use App\Dto\ContactDTO;

class ContactRequestEvent
{

    public function __construct(
        public ContactDTO $data
    )
    {
    }

}