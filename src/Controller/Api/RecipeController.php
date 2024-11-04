<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\PaginationDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RecipeController extends AbstractController
{

    public function __construct(
        private RecipeRepository $recipeRepository
    )
    {

    }

    #[Route('/api/recipes', name: 'recipes.index', methods: ['GET'])]
    public function index(
        Request $request,
        #[MapQueryString]
        ?PaginationDTO $paginationDTO = null,
    ): JsonResponse
    {
        $page = $paginationDTO?->page ?? 1;
        $recipes = $this->recipeRepository->paginatedRecipes(page: $page, userId: null, limit: 5);

        return $this->json($recipes, 200, [], [
            'groups' => 'recipes:index',
        ]);
    }

    //create a new recipe
    #[Route('/api/recipes', name: 'recipes.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            acceptFormat: 'json',
            serializationContext: ['groups' => ['recipes:create']]
        )]
        Recipe $recipe
    ): JsonResponse
    {
        /*
        $recipe = new Recipe();

        $recipe = $serializer->deserialize(  $request->getContent(), Recipe::class, 'json', [
                AbstractNormalizer::OBJECT_TO_POPULATE => $recipe,
                'groups' => ['recipes:create'],
            ]
        );
        */

        $this->recipeRepository->persist($recipe);
        $this->recipeRepository->flush();

        return $this->json($recipe, 201, [], [
            'groups' => 'recipes:index',
        ]);
    }

}