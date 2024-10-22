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


    #[ORM\ManyToOne(inversedBy: 'eventRewards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    /**
     * @var Collection<int, PrizePack>
     */
    #[ORM\OneToMany(targetEntity: PrizePack::class, mappedBy: 'eventReward', orphanRemoval: true)]
    private Collection $prizePacks;

    /**
     * @var Collection<int, Recipient>
     */
    #[ORM\OneToMany(targetEntity: Recipient::class, mappedBy: 'eventReward', orphanRemoval: true)]
    private Collection $recipients;

    public function __construct()
    {
        $this->prizePacks = new ArrayCollection();
        $this->recipients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $prizePack->setEventReward($this);
        }

        return $this;
    }

    public function removePrizePack(PrizePack $prizePack): static
    {
        if ($this->prizePacks->removeElement($prizePack)) {
            // set the owning side to null (unless already changed)
            if ($prizePack->getEventReward() === $this) {
                $prizePack->setEventReward(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipient>
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    public function addRecipient(Recipient $recipient): static
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients->add($recipient);
            $recipient->setEventReward($this);
        }

        return $this;
    }

    public function removeRecipient(Recipient $recipient): static
    {
        if ($this->recipients->removeElement($recipient)) {
            // set the owning side to null (unless already changed)
            if ($recipient->getEventReward() === $this) {
                $recipient->setEventReward(null);
            }
        }

        return $this;
    }
}
