<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RecipeController extends AbstractController
{
    //add demo
    #[Route('/demo', name: 'recipe.demo')]
    public function demo(ValidatorInterface $validator): Response
    {
        $data = 'Hello World';
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
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'recipe.new')]
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
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $em->flush();
            //flash
            $this->addFlash('success', 'Recette modifiée avec succès');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/edit.html.twig', [
            'form' => $form,
        ]);
    }

    //recipe.delete
    #[Route('/recettes/{id}/delete', name: 'recipe.delete', methods: ['DELETE'])]
    public function delete(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'Recette supprimée avec succès');

        return $this->redirectToRoute('recipe.index');
    }
}
