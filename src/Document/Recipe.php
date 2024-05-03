<?php
// src/Document/Recipe.php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
// use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Metadata\ApiResource;
use App\Interface\Models\IngredientInterface;
use App\Interface\Models\RecipeInterface;
use App\Interface\Models\RecipeStepInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
#[MongoDB\Document]
class Recipe implements RecipeInterface
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'string')]
    #[Groups(['read', 'write'])]
    private $title;

    #[MongoDB\ReferenceMany(targetDocument: Ingredient::class, mappedBy: 'recipe', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private $ingredients;

    #[MongoDB\ReferenceMany(targetDocument: RecipeStep::class, mappedBy: 'recipe', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private $steps;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->steps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getIngredients()
    {
        return $this->ingredients;
    }

    public function addIngredient(IngredientInterface $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }
        return $this;
    }

    public function removeIngredient(IngredientInterface $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }
        return $this;
    }

    public function getSteps()
    {
        return $this->steps;
    }

    public function addStep(RecipeStepInterface $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setRecipe($this);
        }
        return $this;
    }

    public function removeStep(RecipeStepInterface $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }
        return $this;
    }
}
