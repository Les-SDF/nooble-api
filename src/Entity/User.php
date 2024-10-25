<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
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
        new Delete(),
        new Patch(
            denormalizationContext: ["groups" => ["user:update"]],
            validationContext: ["groups" => ["Default", "user:update"]],
            processor: UserProcessor::class
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
    #[Groups(["user:read", "user:create", "register:read", "participations:read", "participation:read"])]
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
     * @var Collection<int, Belong>
     */
    #[ORM\OneToMany(targetEntity: Belong::class, mappedBy: 'user', orphanRemoval: true)]
    #[Groups(["user:read", "register:read"])]
    private Collection $belongs;

    /**
     * @var Collection<int, Register>
     */
    #[ORM\OneToMany(targetEntity: Register::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $registers;

    /**
     * @var Collection<int, Manager>
     */
    #[ORM\OneToMany(targetEntity: Manager::class, mappedBy: 'users', orphanRemoval: true)]
    private Collection $managers;

    /**
     * @var Collection<int, Reward>
     */
    #[ORM\OneToMany(targetEntity: Reward::class, mappedBy: 'manager', orphanRemoval: true)]
    private Collection $rewards;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'creator')]
    private Collection $createdEvents;

    public function __construct()
    {
        $this->belongs = new ArrayCollection();
        $this->registers = new ArrayCollection();
        $this->managers = new ArrayCollection();
        $this->rewards = new ArrayCollection();
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
     * @return Collection<int, Belong>
     */
    public function getBelongs(): Collection
    {
        return $this->belongs;
    }

    public function addBelong(Belong $belong): static
    {
        if (!$this->belongs->contains($belong)) {
            $this->belongs->add($belong);
            $belong->setUser($this);
        }

        return $this;
    }

    public function removeBelong(Belong $belong): static
    {
        if ($this->belongs->removeElement($belong)) {
            // set the owning side to null (unless already changed)
            if ($belong->getUser() === $this) {
                $belong->setUser(null);
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
            $register->setUser($this);
        }

        return $this;
    }

    public function removeRegister(Register $register): static
    {
        if ($this->registers->removeElement($register)) {
            // set the owning side to null (unless already changed)
            if ($register->getUser() === $this) {
                $register->setUser(null);
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
    public function getRewards(): Collection
    {
        return $this->rewards;
    }

    public function addReward(Reward $reward): static
    {
        if (!$this->rewards->contains($reward)) {
            $this->rewards->add($reward);
            $reward->setManager($this);
        }

        return $this;
    }

    public function removeReward(Reward $reward): static
    {
        if ($this->rewards->removeElement($reward)) {
            // set the owning side to null (unless already changed)
            if ($reward->getManager() === $this) {
                $reward->setManager(null);
            }
        }

        return $this;
    }

    // TODO: Enlever cette méthode, elle règle un problème de sérialisation pour AppFixtures
    public function __toString(): string
    {
        return 'User';
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
}