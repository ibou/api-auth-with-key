<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class PaginationDTO
{

    public function __construct(
        #[Assert\Positive]
        public ?int $page = 1,
    ) {
    }

}