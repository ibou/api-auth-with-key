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
use App\Entity\Game;
use App\State\EntityClassDtoStateProcessor;
use App\State\EntityClassDtoStateProvider;

#[ApiResource(
    shortName: 'Game',
    operations: [
        new Get(
            security: 'is_granted("ROLE_GAME_READ")'
        ),
        new Post(
            security: 'is_granted("ROLE_GAME_CREATE")'
        ),
        new GetCollection( ),
        new Delete(
            security: 'is_granted("ROLE_GAME_DELETE")'
        ),
        new Patch(
            security: 'is_granted("ROLE_GAME_UPDATE")'
        ),

    ],
    paginationItemsPerPage: 5,
    security: 'is_granted("ROLE_GAME_READ")',
    provider: EntityClassDtoStateProvider::class,
    processor: EntityClassDtoStateProcessor::class,
    stateOptions: new Options(entityClass: Game::class),

)]
class GameApi
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    public ?string $name = null;

    public ?bool $isMultiplayer = false;

    public ?\DateTimeImmutable $releaseDate = null;

    public ?UserApi $owner = null;

}