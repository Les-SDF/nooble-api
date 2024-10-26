<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use App\Repository\ConfrontationRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
//TODO: RAJOUTER Game.
#[ORM\Entity(repositoryClass: ConfrontationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/game/{id}/confrontations",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "confrontations",
                    fromClass: Game::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/event/{id}/confrontations",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "confrontations",
                    fromClass: Event::class
                )
            ]
        ),
        new Patch(
            denormalizationContext: ["groups" => ["confrontation:update"]],
            security: "is_granted('CONFRONTATION_UPDATE', object)",
            validationContext: ["groups" => ["confrontation:update"]],
        )
    ],
    normalizationContext: ["groups" => ["confrontation:read"]]
)]
class Confrontation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Encounter>
     */
    #[ORM\OneToMany(targetEntity: Encounter::class, mappedBy: 'confrontation', orphanRemoval: true)]
    #[Groups(["confrontations:read", "confrontation:read", "confrontation:update"])]
    private Collection $encounters;

    #[ORM\Column]
    #[Groups(["confrontations:read", "confrontation:read", "confrontation:update"])]
    private ?int $round = null;

    #[ORM\ManyToOne(inversedBy: 'confrontations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(cascade: ["persist"], inversedBy: 'confrontations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["confrontations:read", "confrontation:read", "confrontation:update"])]
    private ?Game $game = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $date = null;

    public function __construct()
    {
        $this->encounters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection<int, Encounter>
     */
    public function getEncounters(): Collection
    {
        return $this->encounters;
    }

    public function addEncounter(Encounter $encounter): static
    {
        if (!$this->encounters->contains($encounter)) {
            $this->encounters->add($encounter);
            $encounter->setConfrontation($this);
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): static
    {
        if ($this->encounters->removeElement($encounter)) {
            // set the owning side to null (unless already changed)
            if ($encounter->getConfrontation() === $this) {
                $encounter->setConfrontation(null);
            }
        }

        return $this;
    }

    public function getRound(): ?int
    {
        return $this->round;
    }

    public function setRound(int $round): static
    {
        $this->round = $round;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }
}