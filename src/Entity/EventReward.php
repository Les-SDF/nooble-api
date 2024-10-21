<?php

namespace App\Entity;

use App\Repository\EventRewardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRewardRepository::class)]
class EventReward
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, PrizePack>
     */
    #[ORM\ManyToMany(targetEntity: PrizePack::class, mappedBy: 'eventRewards')]
    private Collection $prizePacks;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'eventRewards')]
    private Collection $recipients;

    #[ORM\ManyToOne(inversedBy: 'eventRewards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    public function __construct()
    {
        $this->prizePacks = new ArrayCollection();
        $this->recipients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, PrizePack>
     */
    public function getPrizePacks(): Collection
    {
        return $this->prizePacks;
    }

    public function addPrizePack(PrizePack $prizePack): static
    {
        if (!$this->prizePacks->contains($prizePack)) {
            $this->prizePacks->add($prizePack);
            $prizePack->addEventReward($this);
        }

        return $this;
    }

    public function removePrizePack(PrizePack $prizePack): static
    {
        if ($this->prizePacks->removeElement($prizePack)) {
            $prizePack->removeEventReward($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    public function addRecipient(Team $recipient): static
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients->add($recipient);
        }

        return $this;
    }

    public function removeRecipient(Team $recipient): static
    {
        $this->recipients->removeElement($recipient);

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
}
