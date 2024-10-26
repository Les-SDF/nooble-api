<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource()]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["confrontations:read", "confrontation:read"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["confrontations:read", "confrontation:read"])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["confrontations:read"])]

    private ?string $imageUrl = null;

    /**
     * @var Collection<int, Confrontation>
     */
    #[ORM\OneToMany(targetEntity: Confrontation::class, mappedBy: 'game', orphanRemoval: false)]
    private Collection $confrontations;

    public function __construct()
    {
        $this->confrontations = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return Collection<int, Confrontation>
     */
    public function getConfrontations(): Collection
    {
        return $this->confrontations;
    }

    public function addConfrontation(Confrontation $confrontation): static
    {
        if (!$this->confrontations->contains($confrontation)) {
            $this->confrontations->add($confrontation);
            $confrontation->setGame($this);
        }

        return $this;
    }

    public function removeConfrontation(Confrontation $confrontation): static
    {
        if ($this->confrontations->removeElement($confrontation)) {
            // set the owning side to null (unless already changed)
            if ($confrontation->getGame() === $this) {
                $confrontation->setGame(null);
            }
        }

        return $this;
    }
}