<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
#[ORM\Table(name: 'favorites')]
class Favorite
{
    /**
     * INFO: Set a maximum number of items in the favorites table
     */
    public const MAX_FAVORITE = 10;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'favorite', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fruit $fruit = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAdded = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFruit(): ?Fruit
    {
        return $this->fruit;
    }

    public function setFruit(Fruit $fruit): static
    {
        $this->fruit = $fruit;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->dateAdded;
    }

    public function setDateAdded(?\DateTimeInterface $dateAdded): static
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
