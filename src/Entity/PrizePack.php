<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use App\Repository\PrizePackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrizePackRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/reward/{id}/prizePacks",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "prizePacks",
                    fromClass: Reward::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/eventReward/{id}/prizePacks",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "prizePacks",
                    fromClass: EventReward::class
                )
            ]
        ),
        new Patch(
            denormalizationContext: ["groups" => ["prizepack:update"]],
            security: "is_granted('PRIZE_PACK_UPDATE', object)",
            validationContext: ["groups" => ["prizepack:update"]],
        ),
        new Delete(
            security: "is_granted('PRIZE_PACK_DELETE', object)",
        )
    ]
)]
class PrizePack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(options: ["default" => 1])]
    #[Groups(["event:read", "prizepack:update"])]
    private ?int $quantity = 1;

    #[ORM\ManyToOne(inversedBy: 'prizePacks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["event:read", "prizepack:update"])]
    private ?Reward $reward = null;

    #[ORM\ManyToOne(inversedBy: 'prizePacks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["prizepack:update"])]
    private ?EventReward $eventReward = null;

    public function __construct() {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getReward(): ?Reward
    {
        return $this->reward;
    }

    public function setReward(?Reward $reward): static
    {
        $this->reward = $reward;

        return $this;
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
}