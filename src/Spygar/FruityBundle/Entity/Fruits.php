<?php

namespace Spygar\FruityBundle\Entity;

use Spygar\FruityBundle\Repository\FruitsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitsRepository::class)]
class Fruits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $genus = null;

    #[ORM\Column(length: 10)]
    private ?int $fruitId;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $family = null;

    #[ORM\Column(length: 50)]
    private ?string $fruitOrder = null;

    #[ORM\Column(length: 10)]
    private ?bool $isFavorite;

    #[ORM\Column(nullable: true)]
    private array $nutritions = [];

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getFruitId(): ?string
    {
        return $this->fruitId;
    }

    public function setFruitId(string $fruitId): self
    {
        $this->fruitId = $fruitId;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getFruitOrder(): ?string
    {
        return $this->fruitOrder;
    }

    public function setFruitOrder(string $fruitOrder): self
    {
        $this->fruitOrder = $fruitOrder;

        return $this;
    }
    
    public function getIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }
    public function getNutritions(): array
    {
        return $this->nutritions;
    }

    public function setNutritions(?array $nutritions): self
    {
        $this->nutritions = $nutritions;

        return $this;
    }
}
