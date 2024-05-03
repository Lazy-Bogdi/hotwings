<?php


namespace App\Interface\Models;

interface RecipeStepInterface
{
    public function getId();
    public function getRecipe(): ?RecipeInterface;
    public function setRecipe(?RecipeInterface $recipe);
    public function getContent(): ?string;
    public function setContent(string $content);
    public function getTotalSteps(): ?int;
    public function setTotalSteps(?int $totalSteps): static;
    public function getStepNumber(): ?int;
    public function setStepNumber(?int $stepNumber): static;
}
