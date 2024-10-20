<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\GameApi;
use App\ApiResource\UserApi;
use App\Entity\Game;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: Game::class, to: GameApi::class)]
class GameEntityToApiMapper implements MapperInterface
{

    public function __construct(
        private MicroMapperInterface $microMapper,
    )
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
        $entity = $from;
        assert($entity instanceof Game);

        $dto = new GameApi();
        $dto->id = $entity->getId();

        return $dto;

    }


    public function populate(object $from, object $to, array $context): object
    {
       $entity = $from;
        $dto = $to;
        assert($entity instanceof Game);
        assert($dto instanceof GameApi);

        $dto->name = $entity->getName();
        $dto->isMultiplayer = $entity->getIsMultiplayer();
        $dto->releaseDate = $entity->getReleaseDate();
        if($entity->getOwner()) {
            $dto->owner = $this->microMapper->map($entity->getOwner(), UserApi::class, [
                MicroMapperInterface::MAX_DEPTH => 0,
            ]);
        } else {
            $dto->owner = null;
        }
        return $dto;
    }
}