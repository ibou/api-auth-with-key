<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Dto\CityOutput;
use App\Repository\CityRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CityStateProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider,
        #[Autowire(service: ItemProvider::class)] private ProviderInterface       $itemProvider,
        private CityRepository                                                    $cityRepository,
        private readonly Pagination $pagination

    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $resourceClass = $operation->getClass();

        if ($operation instanceof CollectionOperationInterface) {
            $cities = $this->collectionProvider->provide($operation, $uriVariables, $context);

            $dtos =   array_map(
                fn($city) => $this->createCityOutput($city),
                iterator_to_array($cities)
            );

            $currentPage = $this->pagination->getPage(context: $context);
            $offset = $this->pagination->getOffset(operation: $operation, context: $context);
            $itemsPerPage = $this->pagination->getLimit(operation: $operation, context: $context);
            $totalItems = $this->cityRepository->count([]);

            return new TraversablePaginator(
                traversable: new \ArrayIterator($dtos),
                currentPage: $currentPage,
                itemsPerPage: $itemsPerPage,
                totalItems: $totalItems,
            );
            return $cities;
        }

        $city = $this->itemProvider->provide($operation, $uriVariables, $context);
        if (null === $city) {
            return null;
        }

        return $this->createCityOutput($city);

    }


    /**
     * @param array<string, mixed> $context
     * @return array<CityOutput>
     */
    private function getCollection(array $context): array
    {
        $filters = $context['filters'] ?? [];
        $cities = $this->cityRepository->findAll();

        return array_map(
            fn($city) => $this->createCityOutput($city),
            $cities
        );
    }

    private function createCityOutput($city): CityOutput
    {

        $cityDto = new CityOutput( );
        $cityDto->id = $city->getId();
        $cityDto->name = $city->getName();
        $cityDto->zipCode = $city->getZipCode();
        $cityDto->fullAddress = $city->getFullAddress();
        $cityDto->country ='Velszzo';
        return $cityDto;

    }

    private function getItem(int $id): CityOutput
    {
        $city = $this->cityRepository->find($id);

        if (!$city) {
            throw new NotFoundHttpException('City not found');
        }

        return $this->createCityOutput($city);
    }
}