<?php

namespace App\Entity;

use App\Entity\Recipe;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\IngredientRepository;
use App\Interface\Models\RecipeInterface;
use App\Interface\Models\IngredientInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
// #[ApiResource]
class Ingredient implements IngredientInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    private ?Recipe $recipe = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?RecipeInterface
    {
        return $this->recipe;
    }

    public function setRecipe(?RecipeInterface $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
