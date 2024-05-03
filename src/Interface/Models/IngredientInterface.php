<?php

namespace App\Interface\Models;

interface IngredientInterface
{
    public function getId();
    public function getRecipe(): ?RecipeInterface;
    public function setRecipe(?RecipeInterface $recipe);
    public function getContent(): ?string;
    public function setContent(string $content);
}
