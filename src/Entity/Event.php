<?php
/**
 * @noinspection NestedPositiveIfStatementsInspection
 * @noinspection PhpUnused
 */

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\RegistrationStatus;
use App\Enum\EventStatus;
use App\Enum\Visibility;
use App\Repository\EventRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => [
        self::READ_GROUP,
        // Confrontation::READ_COLLECTION_GROUP,
        // CustomerRegistration::READ_GROUP,
        ]
    ]
)]
#[GetCollection(
    normalizationContext: ["groups" => [self::READ_GROUP]]
)]
#[GetCollection(
    uriTemplate: "/users/{id}/events",
    uriVariables: [
        "id" => new Link(
            fromProperty: 'createdEvents',
            fromClass: User::class
        )
    ],
    normalizationContext: ["groups" => ["create:read"]],
    security: "is_granted('ROLE_ADMIN') or (is_granted('ROLE_ORGANISER') and request.attributes.get('id') == user.getId())"
)]
#[GetCollection(
    uriTemplate: "/events/{id}/teams",
    uriVariables: [
        "id" => new Link(
            fromProperty: "event",
            fromClass: TeamRegistration::class
        )
    ],
    normalizationContext: ["groups" => [Team::READ_COLLECTION_GROUP]],
    security: "is_granted('ROLE_ADMIN')"
)]
#[Get]
#[Post(
    denormalizationContext: ["groups" => [User::CREATE_GROUP]],
    security: "is_granted('EVENT_CREATE', object)",
    validationContext: ["groups" => [User::CREATE_GROUP]],
)]
#[Patch(
    denormalizationContext: ["groups" => [User::UPDATE_GROUP]],
    security: "is_granted('EVENT_UPDATE', object)",
    validationContext: ["groups" => [User::UPDATE_GROUP]],
)]
#[Delete(
    security: "is_granted('EVENT_DELETE', object)"
)]
class Event
{
    public const READ_GROUP = "event:read";
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["event:read", User::READ_GROUP])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        self::READ_GROUP,
        Confrontation::READ_COLLECTION_GROUP,
        CustomerRegistration::READ_GROUP,
        "create:read",
    ])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        self::READ_GROUP,
        CustomerRegistration::READ_GROUP,
        "create:read",
    ])]
    private ?DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        self::READ_GROUP,
        CustomerRegistration::READ_GROUP,
        "create:read"
    ])]
    private ?DateTimeImmutable $endDate = null;

    #[ORM\Column(
        type: Types::TEXT,
        nullable: true
    )]
    #[Groups([
        self::READ_GROUP,
        "create:read"
    ])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups([
        self::READ_GROUP,
        "create:read"
    ])]
    private ?bool $official = null;

    #[ORM\Column(options: ["default" => false])]
    #[Groups([
        self::READ_GROUP,
        "create:read"
    ])]
    private ?bool $charity = false;

    #[ORM\Column(
        enumType: Visibility::class,
        options: ["default" => Visibility::Public]
    )]
    #[Groups("create:read")]
    private ?Visibility $participantsVisibility = null;

    #[Groups([
        self::READ_GROUP,
        "create:read"
    ])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[Groups([
        self::READ_GROUP,
        CustomerRegistration::READ_GROUP,
        "create:read"
    ])]
    #[ORM\Column(enumType: EventStatus::class)]
    private ?EventStatus $status = null;

    /**
     * @var Collection<int, EventReward>
     */
    #[ORM\OneToMany(
        targetEntity: EventReward::class,
        mappedBy: 'event',
        orphanRemoval: true
    )]
    #[Groups([self::READ_GROUP])]
    private Collection $eventRewards;

    #[ORM\Column(nullable: true)]
    #[Groups([
        self::READ_GROUP,
        CustomerRegistration::READ_GROUP
    ])]
    private ?int $maxParticipants = null;

    /**
     * @var Collection<int, EventSponsor>
     */
    #[ORM\OneToMany(
        targetEntity: EventSponsor::class,
        mappedBy: 'event',
        orphanRemoval: true
    )]
    #[Groups([self::READ_GROUP])]
    private Collection $eventSponsors;

    /**
     * @var Collection<int, CustomerRegistration>
     */
    #[ORM\OneToMany(
        targetEntity: CustomerRegistration::class,
        mappedBy: 'event', orphanRemoval: true
    )]
    #[Groups([CustomerRegistration::READ_GROUP])]
    private Collection $customerRegistrations;

    /**
     * @var Collection<int, Confrontation>
     */
    #[ORM\OneToMany(
        targetEntity: Confrontation::class,
        mappedBy: 'event',
        orphanRemoval: true
    )]
    #[Groups([Confrontation::READ_COLLECTION_GROUP])]
    private Collection $confrontations;

    /**
     * @var Collection<int, Manager>
     */
    #[ORM\OneToMany(
        targetEntity: Manager::class,
        mappedBy: 'events',
        orphanRemoval: true
    )]
    private Collection $managers;

    #[ORM\ManyToOne(inversedBy: 'createdEvents')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        self::READ_GROUP,
        Confrontation::READ_COLLECTION_GROUP,
        CustomerRegistration::READ_GROUP,
        "create:read",
    ])]
    private ?User $creator = null;

    /**
     * @var Collection<int, TeamRegistration>
     */
    #[ORM\OneToMany(
        targetEntity: TeamRegistration::class,
        mappedBy: 'event',
        orphanRemoval: true
    )]
    #[Groups(Team::READ_COLLECTION_GROUP)]
    private Collection $teamRegistrations;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    public function __construct()
    {
        $this->eventRewards = new ArrayCollection();
        $this->eventSponsors = new ArrayCollection();
        $this->customerRegistrations = new ArrayCollection();
        $this->confrontations = new ArrayCollection();
        $this->managers = new ArrayCollection();
        $this->teamRegistrations = new ArrayCollection();
    }

    /**
     * TODO: Je ne suis pas sûr que ce soit la bonne manière de retourner les bonnes ressources TeamRegistration
     * @return array
     */
    #[Groups([self::READ_GROUP])]
    public function getPublicTeamRegistration(): array
    {
        if ($this->getParticipantsVisibility() === Visibility::Public) {
            return $this->teamRegistrations
                ->filter(function ($teamRegistration) {
                    /**
                     * @var TeamRegistration $teamRegistration
                     */
                    return $teamRegistration->getStatus() === RegistrationStatus::Accepted;
                })
                ->map(function ($teamRegistration) {
                    /**
                     * @var TeamRegistration $teamRegistration
                     */
                    return $teamRegistration->getTeam()?->getName();
                })
                ->toArray();
        }
        return [];
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

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeImmutable $endDate): static
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

    public function getStatus(): ?EventStatus
    {
        return $this->status;
    }

    public function setStatus(EventStatus $status): static
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
     * @return Collection<int, CustomerRegistration>
     */
    public function getCustomerRegistrations(): Collection
    {
        return $this->customerRegistrations;
    }

    public function addCustomerRegistration(CustomerRegistration $customerRegistration): static
    {
        if (!$this->customerRegistrations->contains($customerRegistration)) {
            $this->customerRegistrations->add($customerRegistration);
            $customerRegistration->setEvent($this);
        }

        return $this;
    }

    public function removeCustomerRegistration(CustomerRegistration $customerRegistration): static
    {
        if ($this->customerRegistrations->removeElement($customerRegistration)) {
            // set the owning side to null (unless already changed)
            if ($customerRegistration->getEvent() === $this) {
                $customerRegistration->setEvent(null);
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
     * @return Collection<int, TeamRegistration>
     */
    public function getTeamRegistrations(): Collection
    {
        return $this->teamRegistrations;
    }

    public function addTeamRegistration(TeamRegistration $teamRegistration): static
    {
        if (!$this->teamRegistrations->contains($teamRegistration)) {
            $this->teamRegistrations->add($teamRegistration);
            $teamRegistration->setEvent($this);
        }

        return $this;
    }

    public function removeTeamRegistration(TeamRegistration $teamRegistration): static
    {
        if ($this->teamRegistrations->removeElement($teamRegistration)) {
            // set the owning side to null (unless already changed)
            if ($teamRegistration->getEvent() === $this) {
                $teamRegistration->setEvent(null);
            }
        }

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
