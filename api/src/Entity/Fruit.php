<?php

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FruitRepository::class)]
#[ORM\Table(name: 'fruits')]
class Fruit
{
    /**
     * @INFO: This should be the value if the data came from https://fruityvice.com/#
     */
    public const SOURCE_FETCHED_API = 'FRUITY_VICE_API';

    /**
     * @INFO: This should be the value if the data came the app itself
     */
    public const SOURCE_FROM_APP = 'FRUITY_VICE_APP';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Name cannot be blank")]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: "Name must be at least 4 characters long.",
        maxMessage: "Name cannot be longer than 50 characters."
    )]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Genus cannot be blank")]
    #[Assert\Length(
        min: 4,
        max: 20,
        minMessage: "Genus must be at least 4 characters long.",
        maxMessage: "Genus cannot be longer than 20 characters."
    )]
    private ?string $genus = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Family cannot be blank")]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: "Family must be at least 4 characters long.",
        maxMessage: "Family cannot be longer than 50 characters."
    )]
    private ?string $family = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "Fruit order cannot be blank")]
    #[Assert\Length(
        min: 4,
        max: 30,
        minMessage: "Fruit order must be at least 4 characters long.",
        maxMessage: "Fruit order cannot be longer than 30 characters."
    )]
    private ?string $fruitOrder = null;

    #[ORM\Column(nullable: true)]
    private ?float $carbohydrates = null;

    #[ORM\Column(nullable: true)]
    private ?float $fat = null;

    #[ORM\Column(nullable: true)]
    private ?float $protein = null;

    #[ORM\Column(nullable: true)]
    private ?float $sugar = null;

    #[ORM\Column(nullable: true)]
    private ?float $calories = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $source = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'fruit', cascade: ['persist', 'remove'])]
    private ?Favorite $favorite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): static
    {
        $this->genus = $genus;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): static
    {
        $this->family = $family;

        return $this;
    }

    public function getFruitOrder(): ?string
    {
        return $this->fruitOrder;
    }

    public function setFruitOrder(string $fruitOrder): static
    {
        $this->fruitOrder = $fruitOrder;

        return $this;
    }

    public function getCarbohydrates(): ?float
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(?float $carbohydrates): static
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(?float $fat): static
    {
        $this->fat = $fat;

        return $this;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein(?float $protein): static
    {
        $this->protein = $protein;

        return $this;
    }

    public function getSugar(): ?float
    {
        return $this->sugar;
    }

    public function setSugar(?float $sugar): static
    {
        $this->sugar = $sugar;

        return $this;
    }

    public function getCalories(): ?float
    {
        return $this->calories;
    }

    public function setCalories(?float $calories): static
    {
        $this->calories = $calories;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setTimestampsOnCreate(): void
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    #[ORM\PreUpdate]
    public function setTimestampsOnUpdate(): void
    {
        $this->updatedAt = new \DateTime('now');
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function getFavorite(): ?Favorite
    {
        return $this->favorite;
    }

    public function setFavorite(Favorite $favorite): static
    {
        // set the owning side of the relation if necessary
        if ($favorite->getFruit() !== $this) {
            $favorite->setFruit($this);
        }

        $this->favorite = $favorite;

        return $this;
    }
}
