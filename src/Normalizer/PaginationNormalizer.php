<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Recipe;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormalizer implements NormalizerInterface
{

    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer
    )
    {
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        assert($object instanceof PaginationInterface);
        return [
            'items' => array_map(fn(Recipe $recipe) => $this->normalizer->normalize($recipe, $format, $context), $object->getItems()),
            'itemsPerPage' => $object->getItemNumberPerPage(),
            'currentPage' => $object->getCurrentPageNumber(),
            'totalPages' => $object->getPageCount(),
        ];

    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {

        return assert($data instanceof PaginationInterface);

    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true,
        ];
    }
}