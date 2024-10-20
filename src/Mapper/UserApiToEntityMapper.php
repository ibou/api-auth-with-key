<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\UserApi;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: UserApi::class, to: User::class)]
class UserApiToEntityMapper implements MapperInterface
{

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private MicroMapperInterface $microMapper,
        private PropertyAccessorInterface $propertyAccessor,
    )
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
       $dto = $from;
        assert($dto instanceof UserApi);
        $userEntity = $dto->id ? $this->userRepository->find($dto->id) : new User();
        if(!$userEntity) {
            throw new Exception('User not found');
        }

        return $userEntity;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        assert($dto instanceof UserApi);
        assert($entity instanceof User);

        $entity->setEmail($dto->email);
        $entity->setUsername($dto->username);

        if($dto->password) {
            $entity->setPassword($this->passwordHasher->hashPassword($entity, $dto->password));
        }

        $games = [];
        foreach ($dto->games as $gameApi) {
            $games[] = $this->microMapper->map($gameApi, Game::class, [
                MicroMapperInterface::MAX_DEPTH => 0,
            ]);
        }
        $this->propertyAccessor->setValue($entity, 'games', $games);

        return $entity;
    }
}