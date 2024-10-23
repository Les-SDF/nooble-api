<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\Status;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["event:read"]]
)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["event:read"])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(["event:read"])]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column]
    #[Groups(["event:read"])]
    private ?\DateTimeImmutable $endDate = null;

    #[Groups(["event:read"])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups(["event:read"])]
    #[ORM\Column]
    private ?bool $official = null;

    #[Groups(["event:read"])]
    #[ORM\Column]
    private ?bool $charity = null;

    #[ORM\Column]
    private ?bool $private = null;

    #[Groups(["event:read"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[Groups(["event:read"])]
    #[ORM\Column(enumType: Status::class)]
    private ?Status $status = null;


    /**
     * @var Collection<int, EventReward>
     */
    #[ORM\OneToMany(targetEntity: EventReward::class, mappedBy: 'event', orphanRemoval: true)]
    #[Groups(["event:read"])]
    private Collection $eventRewards;

    #[ORM\Column(nullable: true)]
    #[Groups(["event:read"])]
    private ?int $maxParticipants = null;

    /**
     * @var Collection<int, EventSponsor>
     */
    #[ORM\OneToMany(targetEntity: EventSponsor::class, mappedBy: 'event', orphanRemoval: true)]
    #[Groups(["event:read"])]
    private Collection $eventSponsors;

    /**
     * @var Collection<int, Register>
     */
    #[ORM\OneToMany(targetEntity: Register::class, mappedBy: 'event', orphanRemoval: true)]
    private Collection $registers;

    public function __construct()
    {
        $this->eventRewards = new ArrayCollection();
        $this->eventSponsors = new ArrayCollection();
        $this->registers = new ArrayCollection();
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

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

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

    public function isOfficial(): ?bool
    {
        return $this->official;
    }

    public function setOfficial(bool $official): static
    {
        $this->official = $official;

        return $this;
    }

    public function isCharity(): ?bool
    {
        return $this->charity;
    }

    public function setCharity(bool $charity): static
    {
        $this->charity = $charity;

        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): static
    {
        $this->private = $private;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

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
            $eventReward->setEvent($this);
        }

        return $this;
    }

    public function removeEventReward(EventReward $eventReward): static
    {
        if ($this->eventRewards->removeElement($eventReward)) {
            // set the owning side to null (unless already changed)
            if ($eventReward->getEvent() === $this) {
                $eventReward->setEvent(null);
            }
        }

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(?int $maxParticipants): static
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * @return Collection<int, EventSponsor>
     */
    public function getEventSponsors(): Collection
    {
        return $this->eventSponsors;
    }

    public function addEventSponsor(EventSponsor $eventSponsor): static
    {
        if (!$this->eventSponsors->contains($eventSponsor)) {
            $this->eventSponsors->add($eventSponsor);
            $eventSponsor->setEvent($this);
        }

        return $this;
    }

    public function removeEventSponsor(EventSponsor $eventSponsor): static
    {
        if ($this->eventSponsors->removeElement($eventSponsor)) {
            // set the owning side to null (unless already changed)
            if ($eventSponsor->getEvent() === $this) {
                $eventSponsor->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Register>
     */
    public function getRegisters(): Collection
    {
        return $this->registers;
    }

    public function addRegister(Register $register): static
    {
        if (!$this->registers->contains($register)) {
            $this->registers->add($register);
            $register->setEvent($this);
        }

        return $this;
    }

    public function removeRegister(Register $register): static
    {
        if ($this->registers->removeElement($register)) {
            // set the owning side to null (unless already changed)
            if ($register->getEvent() === $this) {
                $register->setEvent(null);
            }
        }

        return $this;
    }
}
