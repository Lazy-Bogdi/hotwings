<?php
// src/Document/RecipeStep.php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use ApiPlatform\Metadata\ApiResource;
use App\Interface\Models\RecipeInterface;
use App\Interface\Models\RecipeStepInterface;
use Symfony\Component\Serializer\Annotation\Groups;

// #[ApiResource]
#[MongoDB\Document]
class RecipeStep implements RecipeStepInterface
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\ReferenceOne(targetDocument: Recipe::class, inversedBy: 'steps')]
    private $recipe;

    #[MongoDB\Field(type: 'integer')]
    private ?int $totalSteps = null;

    #[MongoDB\Field(type: 'integer')]
    private ?int $stepNumber = null;

    #[MongoDB\Field(type: 'string')]
    #[Groups(['read', 'write'])]
    private $content;

    public function getId()
    {
        return $this->id;
    }

    public function getRecipe(): ?RecipeInterface
    {
        return $this->recipe;
    }

    public function setRecipe(?RecipeInterface $recipe): self
    {
        $this->recipe = $recipe;
        return $this;
    }

    public function getTotalSteps(): ?int
    {
        return $this->totalSteps;
    }

    public function setTotalSteps(?int $totalSteps): static
    {
        $this->totalSteps = $totalSteps;

        return $this;
    }

    public function getStepNumber(): ?int
    {
        return $this->stepNumber;
    }

    public function setStepNumber(?int $stepNumber): static
    {
        $this->stepNumber = $stepNumber;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }
}
