<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Dto\CityInput;
use App\Dto\CityOutput;
use App\Entity\City;
use App\State\CityStateProvider;

#[ApiResource(
    shortName: 'City',
    operations: [
        new Get(),
        new Post(
            input: CityInput::class,
            output: CityOutput::class
        ),
        new GetCollection(
            //output: CityOutput::class,
        ),
        new Delete(),
        new Patch(),


    ],
    paginationItemsPerPage: 5,
    provider: CityStateProvider::class,
    stateOptions: new Options(entityClass: City::class),

)]
class CityApi
{
    #[ApiProperty(readable: true, writable: false, identifier: true)]
    public ?int $id = null;

    public ?string $name = null;

    public ?string $zipCode = null;

    public ?string $fullAddress = null;

    public ?string $country = null;

}