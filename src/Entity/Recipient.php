<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\RecipientRepository;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipientRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/teams/{id}/recipients",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "recipients",
                    fromClass: Team::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/eventReward/{id}/recipients",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "recipients",
                    fromClass: EventReward::class
                )
            ]
        ),
        new Get(),
        new Post(),
        new Delete()
    ]
)]
class Recipient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'recipients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventReward $eventReward = null;

    #[ORM\ManyToOne(inversedBy: 'recipients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventReward(): ?EventReward
    {
        return $this->eventReward;
    }

    public function setEventReward(?EventReward $eventReward): static
    {
        $this->eventReward = $eventReward;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }
}
