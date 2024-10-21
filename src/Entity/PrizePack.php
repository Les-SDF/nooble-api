<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PrizePackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrizePackRepository::class)]
#[ApiResource]
class PrizePack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    /**
     * @var Collection<int, Reward>
     */
    #[ORM\ManyToMany(targetEntity: Reward::class, inversedBy: 'prizePacks')]
    private Collection $rewards;

    /**
     * @var Collection<int, EventReward>
     */
    #[ORM\ManyToMany(targetEntity: EventReward::class, inversedBy: 'prizePacks')]
    private Collection $eventRewards;

    public function __construct()
    {
        $this->rewards = new ArrayCollection();
        $this->eventRewards = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Reward>
     */
    public function getRewards(): Collection
    {
        return $this->rewards;
    }

    public function addReward(Reward $reward): static
    {
        if (!$this->rewards->contains($reward)) {
            $this->rewards->add($reward);
        }

        return $this;
    }

    public function removeReward(Reward $reward): static
    {
        $this->rewards->removeElement($reward);

        return $this;
    }

    /**
     * @return Collection<int, EventReward>
     */
    public function getEventRewards(): Collection
    {
        return $this->eventRewards;
    }

    public function addEventReward(EventReward $eventReward): static
    {
        if (!$this->eventRewards->contains($eventReward)) {
            $this->eventRewards->add($eventReward);
        }

        return $this;
    }

    public function removeEventReward(EventReward $eventReward): static
    {
        $this->eventRewards->removeElement($eventReward);

        return $this;
    }
}