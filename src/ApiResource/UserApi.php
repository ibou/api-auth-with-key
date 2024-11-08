<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\User;
use App\State\EntityClassDtoStateProcessor;
use App\State\EntityClassDtoStateProvider;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            security: 'is_granted("PUBLIC_ACCESS")',
            validationContext: ['groups' => ['Default', 'postValidation']]
        ),
        new Patch(
            security: 'is_granted("ROLE_USER_EDIT")',
        ),
        new Put(),
        new Delete(),
       
    ],
    paginationItemsPerPage: 10,
    security: 'is_granted("ROLE_USER")',
    provider: EntityClassDtoStateProvider::class,
    processor: EntityClassDtoStateProcessor::class,
    stateOptions: new Options( entityClass: User::class ),
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact', 'username' => 'partial'])]
class UserApi
{

    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $username = null;

    #[Assert\NotBlank(groups: ['postValidation'])]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Password must be at least {{ limit }} chars long, right !', groups: ['postValidation'])]
    #[ApiProperty(readable: false)]
    public ?string $password = null;

    public int $flameThrowingDistance = 0;

    /**
     * @var array<int, GameApi>
     */
    #[ApiProperty(
        uriTemplate: '/users/{userId}/games',
    )]
    public ?array $games = [];
}