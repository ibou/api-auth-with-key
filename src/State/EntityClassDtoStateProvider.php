<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use ArrayIterator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfonycasts\MicroMapper\MicroMapperInterface;

class EntityClassDtoStateProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider,
        #[Autowire(service: ItemProvider::class)] private ProviderInterface       $itemProvider,
        private MicroMapperInterface                                              $microMapper,

    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $resourceClass = $operation->getClass();

        if ($operation instanceof CollectionOperationInterface) {
            $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
            assert($entities instanceof Paginator);
            $dtos = [];

            foreach ($entities as $entity) {
                $dtos[] = $this->mapEntityToDto(entity: $entity, resourceClass: $resourceClass);
            }

            return new TraversablePaginator(
                traversable: new ArrayIterator($dtos),
                currentPage: $entities->getCurrentPage(),
                itemsPerPage: $entities->getItemsPerPage(),
                totalItems: $entities->getTotalItems(),
            );
        }

        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);
        if (null === $entity) {
            return null;
        }

        return $this->mapEntityToDto(entity: $entity, resourceClass: $resourceClass);
    }

    private function mapEntityToDto(object $entity, string $resourceClass): object
    {
        return $this->microMapper->map(from: $entity, toClass: $resourceClass);
    }
}