<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\GameApi;
use App\ApiResource\UserApi;
use App\Entity\Game;
use App\Entity\User;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: User::class, to: UserApi::class)]
class UserEntityToApiMapper implements MapperInterface
{

    public function __construct(
        private MicroMapperInterface $microMapper,
    )
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
        $entity = $from;
        assert($entity instanceof User);

        $dto = new UserApi();
        $dto->id = $entity->getId();

        return $dto;

    }


    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        $dto = $to;
        assert($entity instanceof User);
        assert($dto instanceof UserApi);

        $dto->email = $entity->getEmail();
        $dto->username = $entity->getUsername();
        $dto->flameThrowingDistance = rand(1, 100);


        $dto->games = array_map(function(Game $game) {
            return $this->microMapper->map($game, GameApi::class, [
                MicroMapperInterface::MAX_DEPTH => 0,
            ]);
        }, $entity->getGames()->getValues());

        return $dto;
    }
}