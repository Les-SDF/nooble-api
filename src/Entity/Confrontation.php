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

#[ORM\Entity(repositoryClass: ConfrontationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/games/{id}/confrontations",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "confrontations",
                    fromClass: Game::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/events/{id}/confrontations",
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
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'confrontation', orphanRemoval: true)]
    #[Groups(["confrontations:read", "confrontation:read", "confrontation:update"])]
    private Collection $participations;

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
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setConfrontation($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getConfrontation() === $this) {
                $participation->setConfrontation(null);
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