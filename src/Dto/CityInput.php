<?php

declare(strict_types=1);

namespace App\Dto;

class CityInput
{
    public function __construct(
        public string $name,
        public string $zipCode,
        public string $fullAddress,
    )
    {
    }

}