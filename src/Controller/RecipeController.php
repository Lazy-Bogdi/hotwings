<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\RecipeStep;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    // #[Route('/api/recipes', name: 'add_recipe', methods: ['POST'])]
    // public function addRecipe(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $data = json_decode($request->getContent(), true);

    //     foreach ($data as $recipeData) {
    //         $recipe = new Recipe();
    //         $recipe->setTitle($recipeData['title']);

    //         foreach ($recipeData['ingredients'] as $ingredientData) {
    //             $ingredient = new Ingredient();
    //             $ingredient->setContent($ingredientData);
    //             $recipe->addIngredient($ingredient);
    //         }

    //         foreach ($recipeData['steps'] as $stepData) {
    //             $step = new RecipeStep();
    //             $step->setContent($stepData);
    //             $recipe->addStep($step);
    //         }

    //         $entityManager->persist($recipe);
    //     }

    //     $entityManager->flush();

    //     return new Response('Recipes added', Response::HTTP_CREATED);
    // }
}
