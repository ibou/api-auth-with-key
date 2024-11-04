<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Message\RecipePDFMessage;
use App\Repository\RecipeRepository;
use App\Security\Voter\RecipeVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\UX\Turbo\TurboBundle;

class RecipeController extends AbstractController
{
    //add demo
    #[Route('/demo', name: 'recipe.demo')]
    public function demo(ValidatorInterface $validator): Response
    {

        $recipe = new Recipe();
        $recipe->setTitle('spam');
        $recipe->setSlug('spam');
        $recipe->setContent('spam');
        $recipe->setDuration(90000);

        $errors = $validator->validate($recipe, );

        dd($errors, (string) $errors);

        return new Response((string)$errors);
    }

    #[Route('/recettes', name: 'recipe.index')]
    #[IsGranted(RecipeVoter::LIST)]
    public function index(Request $request,RecipeRepository $recipeRepository, Security $security): Response
    {
        $page = $request->query->getInt('page', 1);
        $canListAll = $security->isGranted(RecipeVoter::LIST_ALL);
        $userId = $security->getUser()?->getId();
        $recipes = $recipeRepository->paginatedRecipes(page: $page, userId: $canListAll ? null: $userId,  limit: 5);
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/create', name: 'recipe.new')]
    #[IsGranted(RecipeVoter::CREATE)]
    public function new(Request$request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $em->persist($recipe);
            $em->flush();
            //flash
            $this->addFlash('success', 'Recette ajoutée avec succès');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form,
        ]);
    }

    //recettes.edit
    #[Route('/recettes/{id}/edit', name: 'recipe.edit')]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function edit(
        Recipe $recipe,
        Request $request,
        EntityManagerInterface $em,
        MessageBusInterface $bus,
    ): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $em->flush();
            $bus->dispatch(
                new RecipePDFMessage($recipe->getId())
            );
            $this->addFlash('success', 'Recette modifiée avec succès');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/edit.html.twig', [
            'form' => $form,
            'recipe' => $recipe,
        ]);
    }

    //recipe.delete
    #[Route('/recettes/{id}/delete', name: 'recipe.delete', methods: ['DELETE'])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function remove(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $em,
        RecipeRepository $recipeRepository
    ): Response
    {

        $em->remove($recipe);
        $em->flush();
        if($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {
            $this->addFlash('success', 'Recette supprimée avec succès');
            $recipes = $recipeRepository->paginatedRecipes(page: 1, userId: null,  limit: 5);

            return $this->redirectToRoute('recipe.index', [
                'recipes' => $recipes,
            ]);
        }
        $this->addFlash('success', 'Recette supprimée avec succès');

        return $this->redirectToRoute('recipe.index');
    }
}
