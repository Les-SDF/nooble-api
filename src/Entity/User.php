<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\State\UserProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            denormalizationContext: ["groups" => ["user:create"]],
            validationContext: ["groups" => ["Default", "user:create"]],
            processor: UserProcessor::class
        ),
        new Patch(
            denormalizationContext: ["groups" => ["user:update"]],
            validationContext: ["groups" => ["Default", "user:update"]],
            processor: UserProcessor::class
        ),
        new Delete(
            security: "is_granted('USER_DELETE', object)",
        )
    ],
    normalizationContext: ["groups" => ["user:read"]]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    #[Groups(["user:read", "user:create", "customer-registration:read", "confrontations:read", "confrontation:read"])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[ApiProperty(description: 'Hashed password', readable: false, writable: false)]
    private ?string $password = null;

    #[Assert\NotBlank(message: "Le mot de passe actuel ne doit pas être vide", groups: ["user:create"])]
    #[Assert\NotNull(message: "Le mot de passe actuel ne doit pas être null", groups: ["user:create"])]
    #[Assert\Length(min: 8, max: 32, minMessage: "Le mot de passe actuel doit faire plus de 8 caractères", maxMessage: "Le mot de passe actuel doit faire moins de 32 caractères", groups: ["user:create"])]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/',
        message: 'The password must be at least 5 characters long, with at least one lowercase, one uppercase letter and one special character.',
        groups: ["user:create"]
    )]
    #[Groups(["user:create"])]
    #[ApiProperty(description: 'Plain password for creating an user', readable: false, writable: true)]
    private ?string $plainPassword = null;

    #[ApiProperty(description: 'Plain password for updating an user', readable: false, writable: true)]
    #[Groups(["user:update"])]
    #[UserPassword(groups: ["user:update"])]
    private ?string $currentPlainPassword = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[ApiProperty(readable: true, writable: true)]
    #[Groups(["user:read"])]
    private ?string $username = null;

    /**
     * @var Collection<int, Member>
     */
    #[ORM\OneToMany(targetEntity: Member::class, mappedBy: 'user', orphanRemoval: true)]
    #[Groups(["user:read", "customer-registration:read"])]
    private Collection $members;


    /**
     * @var Collection<int, CustomerRegistration>
     */
    #[ORM\OneToMany(targetEntity: CustomerRegistration::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $customerRegistrations;

    /**
     * @var Collection<int, Manager>
     */
    #[ORM\OneToMany(targetEntity: Manager::class, mappedBy: 'users', orphanRemoval: true)]
    private Collection $managers;

    /**
     * @var Collection<int, Reward>
     */
    #[ORM\OneToMany(targetEntity: Reward::class, mappedBy: 'creator', orphanRemoval: true)]
    private Collection $createdRewards;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'creator')]
    #[Groups("create:read")]
    private Collection $createdEvents;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->customerRegistrations = new ArrayCollection();
        $this->managers = new ArrayCollection();
        $this->createdRewards = new ArrayCollection();
        $this->createdEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getCurrentPlainPassword(): ?string
    {
        return $this->currentPlainPassword;
    }

    public function setCurrentPlainPassword(?string $currentPlainPassword): void
    {
        $this->currentPlainPassword = $currentPlainPassword;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }


    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
        $this->currentPlainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $belong): static
    {
        if (!$this->members->contains($belong)) {
            $this->members->add($belong);
            $belong->setUser($this);
        }

        return $this;
    }

    public function removeMember(Member $belong): static
    {
        if ($this->members->removeElement($belong)) {
            // set the owning side to null (unless already changed)
            if ($belong->getUser() === $this) {
                $belong->setUser(null);
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
            $customerRegistration->setUser($this);
        }

        return $this;
    }

    public function removeCustomerRegistration(CustomerRegistration $customerRegistration): static
    {
        if ($this->customerRegistrations->removeElement($customerRegistration)) {
            // set the owning side to null (unless already changed)
            if ($customerRegistration->getUser() === $this) {
                $customerRegistration->setUser(null);
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
            $manager->setUsers($this);
        }

        return $this;
    }

    public function removeManager(Manager $manager): static
    {
        if ($this->managers->removeElement($manager)) {
            // set the owning side to null (unless already changed)
            if ($manager->getUsers() === $this) {
                $manager->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reward>
     */
    public function getCreatedRewards(): Collection
    {
        return $this->createdRewards;
    }

    public function addCreatedReward(Reward $createdReward): static
    {
        if (!$this->createdRewards->contains($createdReward)) {
            $this->createdRewards->add($createdReward);
            $createdReward->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedReward(Reward $createdReward): static
    {
        if ($this->createdRewards->removeElement($createdReward)) {
            // set the owning side to null (unless already changed)
            if ($createdReward->getCreator() === $this) {
                $createdReward->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getCreatedEvents(): Collection
    {
        return $this->createdEvents;
    }

    public function addCreatedEvent(Event $createdEvent): static
    {
        if (!$this->createdEvents->contains($createdEvent)) {
            $this->createdEvents->add($createdEvent);
            $createdEvent->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedEvent(Event $createdEvent): static
    {
        if ($this->createdEvents->removeElement($createdEvent)) {
            // set the owning side to null (unless already changed)
            if ($createdEvent->getCreator() === $this) {
                $createdEvent->setCreator(null);
            }
        }

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if (($key = array_search($role, $this->roles, true)) !== false) {
            unset($this->roles[$key]);
        }

        $this->roles = array_values($this->roles);

        return $this;
    }
}