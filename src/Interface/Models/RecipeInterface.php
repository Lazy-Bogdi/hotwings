<?php

// src/Model/RecipeInterface.php

namespace App\Interface\Models;

interface RecipeInterface
{
    public function getId(): ?int;
    public function getTitle(): ?string;
    public function setTitle(string $title);
    public function getIngredients();
    public function addIngredient(IngredientInterface $ingredient);
    public function removeIngredient(IngredientInterface $ingredient);
    public function getSteps();
    public function addStep(RecipeStepInterface $step);
    public function removeStep(RecipeStepInterface $step);
}
