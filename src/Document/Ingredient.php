<?php
// src/Document/Ingredient.php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
// use ApiPlatform\Core\Annotation\ApiResource;
use App\Interface\Models\RecipeInterface;
use App\Interface\Models\IngredientInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

// #[ApiResource]
#[MongoDB\Document]
class Ingredient implements IngredientInterface
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\ReferenceOne(targetDocument: Recipe::class, inversedBy: 'ingredients')]
    private $recipe;

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
