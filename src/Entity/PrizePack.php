<?php
/**
 * @noinspection NestedPositiveIfStatementsInspection
 * @noinspection PhpUnused
 */

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PrizePackRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrizePackRepository::class)]
#[ApiResource]
#[GetCollection(
    uriTemplate: "/rewards/{id}/prize_packs",
    uriVariables: [
        "id" => new Link(
            fromProperty: "prizePacks",
            fromClass: Reward::class
        )
    ]
)]
#[GetCollection(
    uriTemplate: "/rewards/{id}/prize_packs",
    uriVariables: [
        "id" => new Link(
            fromProperty: "prizePacks",
            fromClass: Reward::class
        )
    ]
)]
#[GetCollection(
    uriTemplate: "/event_rewards/{id}/prize_packs",
    uriVariables: [
        "id" => new Link(
            fromProperty: "prizePacks",
            fromClass: EventReward::class
        )
    ]
)]
#[Post]
#[Patch]
#[Delete]
class PrizePack
{
    public const UPDATE_GROUP = "update_prize_pack:update";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([Event::READ_GROUP])]
    private ?int $id = null;

    #[ORM\Column(options: ["default" => 1])]
    #[Groups([
        self::UPDATE_GROUP,
        Event::READ_GROUP,
    ])]
    private ?int $quantity = 1;

    #[ORM\ManyToOne(inversedBy: 'prizePacks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        self::UPDATE_GROUP,
        Event::READ_GROUP,
    ])]
    private ?Reward $reward = null;

    #[ORM\ManyToOne(inversedBy: 'prizePacks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::UPDATE_GROUP])]
    private ?EventReward $eventReward = null;

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