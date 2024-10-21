<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
}
