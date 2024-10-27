<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\Status;
use App\Enum\Visibility;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('EVENT_READ', object)"
        ),
        new Get(),
        new Post(
            denormalizationContext: ["groups" => ["user:create"]],
            security: "is_granted('EVENT_CREATE', object)",
            validationContext: ["groups" => ["user:create"]],
        ),
        new Patch(
            denormalizationContext: ["groups" => ["user:update"]],
            security: "is_granted('EVENT_UPDATE', object)",
            validationContext: ["groups" => ["user:update"]],
        ),
        new Delete(security: "is_granted('EVENT_DELETE', object)")
    ],
    normalizationContext: ["groups" => ["event:read", "register:read", "confrontations:read"]]
)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["event:read", "register:read", "confrontations:read"])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(["event:read", "register:read"])]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column]
    #[Groups(["event:read", "register:read"])]
    private ?\DateTimeImmutable $endDate = null;

    #[Groups(["event:read"])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups(["event:read"])]
    #[ORM\Column]
    private ?bool $official = null;

    #[Groups(["event:read"])]
    #[ORM\Column(options: ["default" => false])]
    private ?bool $charity = false;

    #[ORM\Column(enumType: Visibility::class, options: ["default" => Visibility::Public])]
    private ?Visibility $participantsVisibility = null;

    #[Groups(["event:read"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[Groups(["event:read", "register:read"])]
    #[ORM\Column(enumType: Status::class)]
    private ?Status $status = null;

    /**
     * @var Collection<int, EventReward>
     */
    #[ORM\OneToMany(targetEntity: EventReward::class, mappedBy: 'event', orphanRemoval: true)]
    #[Groups(["event:read"])]
    private Collection $eventRewards;

    #[ORM\Column(nullable: true)]
    #[Groups(["event:read", "register:read"])]
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
    #[Groups(["register:read"])]
    private Collection $registers;

    /**
     * @var Collection<int, Confrontation>
     */
    #[ORM\OneToMany(targetEntity: Confrontation::class, mappedBy: 'event', orphanRemoval: true)]
    #[Groups(["confrontations:read"])]
    private Collection $confrontations;

    /**
     * @var Collection<int, Manager>
     */
    #[ORM\OneToMany(targetEntity: Manager::class, mappedBy: 'events', orphanRemoval: true)]
    private Collection $managers;

    #[ORM\ManyToOne(inversedBy: 'createdEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    /**
     * @var Collection<int, TeamEvent>
     */
    #[ORM\OneToMany(targetEntity: TeamEvent::class, mappedBy: 'event', orphanRemoval: true)]
    #[Groups(["teams:read"])]
    private Collection $teamEvents;

    #[ORM\Column]
    private ?bool $teamIsVisible = false;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    public function __construct()
    {
        $this->eventRewards = new ArrayCollection();
        $this->eventSponsors = new ArrayCollection();
        $this->registers = new ArrayCollection();
        $this->confrontations = new ArrayCollection();
        $this->managers = new ArrayCollection();
        $this->teamEvents = new ArrayCollection();
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

    public function getParticipantsVisibility(): ?Visibility
    {
        return $this->participantsVisibility;
    }

    public function setParticipantsVisibility(Visibility $participantsVisibility): static
    {
        $this->participantsVisibility = $participantsVisibility;

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

    /**
     * @return Collection<int, Confrontation>
     */
    public function getConfrontations(): Collection
    {
        return $this->confrontations;
    }

    public function addConfrontation(Confrontation $confrontation): static
    {
        if (!$this->confrontations->contains($confrontation)) {
            $this->confrontations->add($confrontation);
            $confrontation->setEvent($this);
        }

        return $this;
    }

    public function removeConfrontation(Confrontation $confrontation): static
    {
        if ($this->confrontations->removeElement($confrontation)) {
            // set the owning side to null (unless already changed)
            if ($confrontation->getEvent() === $this) {
                $confrontation->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Manager>
     */
    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(Manager $manager): static
    {
        if (!$this->managers->contains($manager)) {
            $this->managers->add($manager);
            $manager->setEvents($this);
        }

        return $this;
    }

    public function removeManager(Manager $manager): static
    {
        if ($this->managers->removeElement($manager)) {
            // set the owning side to null (unless already changed)
            if ($manager->getEvents() === $this) {
                $manager->setEvents(null);
            }
        }

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, TeamEvent>
     */
    public function getTeamEvents(): Collection
    {
        return $this->teamEvents;
    }

    public function addTeamEvent(TeamEvent $teamEvent): static
    {
        if (!$this->teamEvents->contains($teamEvent)) {
            $this->teamEvents->add($teamEvent);
            $teamEvent->setEvent($this);
        }

        return $this;
    }

    public function removeTeamEvent(TeamEvent $teamEvent): static
    {
        if ($this->teamEvents->removeElement($teamEvent)) {
            // set the owning side to null (unless already changed)
            if ($teamEvent->getEvent() === $this) {
                $teamEvent->setEvent(null);
            }
        }

        return $this;
    }

    public function isTeamIsVisible(): ?bool
    {
        return $this->teamIsVisible;
    }

    public function setTeamIsVisible(bool $teamIsVisible): static
    {
        $this->teamIsVisible = $teamIsVisible;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }
}