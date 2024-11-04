<?php

declare(strict_types=1);

namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;

class CityOutput
{
    #[ApiProperty(readable: true, writable: false, identifier: true, genId: true)]
    public ?int $id = null;

    public ?string $name = null;

    public ?string $zipCode = null;

    public ?string $fullAddress = null;

    public ?string $country = null;
}