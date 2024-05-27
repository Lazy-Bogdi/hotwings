<?php
// src/Document/RecipeMongo.php

namespace App\Document;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Odm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    paginationEnabled: false,
    operations: [
        new Get(),
        new GetCollection()
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'ingredients' => 'partial'])]
#[MongoDB\Document]
class RecipeMongo
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'string')]
    #[Groups(['read', 'write'])]
    private $title;

    #[MongoDB\Field(type: 'collection')]
    #[Groups(['read', 'write'])]
    private $ingredients = [];

    #[MongoDB\Field(type: 'collection')]
    #[Groups(['read', 'write'])]
    private $steps = [];

    #[MongoDB\Field(type: 'integer')]
    #[Groups(['read', 'write'])]
    private $stepAmount;


    public function getId()
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

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function addIngredient(string $ingredient): self
    {
        if (!in_array($ingredient, $this->ingredients)) {
            $this->ingredients[] = $ingredient;
        }
        return $this;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function addStep(array $step): self
    {
        if (!in_array($step, $this->steps)) {
            $this->steps[] = $step;
        }
        return $this;
    }
}
