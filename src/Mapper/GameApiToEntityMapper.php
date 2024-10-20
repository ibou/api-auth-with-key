<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\GameApi;
use App\ApiResource\UserApi;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\GameRepository;
use Exception;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;

#[AsMapper(from: GameApi::class, to: Game::class)]
class GameApiToEntityMapper implements MapperInterface
{

    public function __construct(
        private GameRepository $gameRepository,
    )
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
        $dto = $from;
        assert($dto instanceof GameApi);
        $gameEntity = $dto->id ? $this->gameRepository->find($dto->id) : new Game();
        if (!$gameEntity) {
            throw new Exception('Game not found');
        }

        return $gameEntity;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        assert($dto instanceof GameApi);
        assert($entity instanceof Game);

        $entity->setName($dto->name);
        $entity->setIsMultiplayer($dto->isMultiplayer);
        $entity->setReleaseDate($dto->releaseDate);

        return $entity;
    }
}