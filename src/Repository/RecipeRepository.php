<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recipe::class);

    }

    public function persist(Recipe $recipe): void
    {
        $this->getEntityManager()->persist($recipe);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function paginatedRecipes(int $page, int $limit): PaginationInterface
    {
       $query = $this->createQueryBuilder('r')
           ->select('r')
            ->getQuery() ;

       $result = $this->paginator->paginate(
           $query,
           $page,
           $limit,
           [
               PaginatorInterface::DEFAULT_SORT_FIELD_NAME => ['r.id', 'r.title'],
                PaginatorInterface::DISTINCT => false,
           ]
       );

        return $result;
    }
}
