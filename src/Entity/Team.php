<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ApiResource()]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'teams')]
    private Collection $members;

    /**
     * @var Collection<int, Sponsor>
     */
    #[ORM\ManyToMany(targetEntity: Sponsor::class, mappedBy: 'teams')]
    private Collection $sponsors;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $participations;

    /**
     * @var Collection<int, Encounter>
     */
    #[ORM\ManyToMany(targetEntity: Encounter::class, mappedBy: 'teams')]
    private Collection $encounters;

    /**
     * @var Collection<int, EventReward>
     */
    #[ORM\ManyToMany(targetEntity: EventReward::class, mappedBy: 'recipients')]
    private Collection $eventRewards;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->encounters = new ArrayCollection();
        $this->sponsors = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->prizePacks = new ArrayCollection();
        $this->eventRewards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        $this->members->removeElement($member);

        return $this;
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
            $encounter->addTeam($this);
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): static
    {
        if ($this->encounters->removeElement($encounter)) {
            $encounter->removeTeam($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Sponsor>
     */
    public function getSponsors(): Collection
    {
        return $this->sponsors;
    }

    public function addSponsor(Sponsor $sponsor): static
    {
        if (!$this->sponsors->contains($sponsor)) {
            $this->sponsors->add($sponsor);
            $sponsor->addTeam($this);
        }

        return $this;
    }

    public function removeSponsor(Sponsor $sponsor): static
    {
        if ($this->sponsors->removeElement($sponsor)) {
            $sponsor->removeTeam($this);
        }

        return $this;
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
            $participation->setTeam($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getTeam() === $this) {
                $participation->setTeam(null);
            }
        }

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
            $eventReward->addRecipient($this);
        }

        return $this;
    }

    public function removeEventReward(EventReward $eventReward): static
    {
        if ($this->eventRewards->removeElement($eventReward)) {
            $eventReward->removeRecipient($this);
        }

        return $this;
    }
}