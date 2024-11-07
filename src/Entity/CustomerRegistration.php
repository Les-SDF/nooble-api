<?php
/**
 * @noinspection NestedPositiveIfStatementsInspection
 * @noinspection PhpUnused
 */

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use App\Enum\RegistrationStatus;
use App\Repository\CustomerRegistrationRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRegistrationRepository::class)]
#[ApiResource]
#[GetCollection(
    uriTemplate: "/users/{id}/customer_registrations",
    uriVariables: [
        "id" => new Link(
            fromProperty: "customerRegistrations",
            fromClass: User::class
        )
    ]
)]
#[GetCollection(
    uriTemplate: "/events/{id}/customer_registrations",
    uriVariables: [
        "id" => new Link(
            fromProperty: "customerRegistrations",
            fromClass: Event::class
        )
    ]
)]
#[Patch(
    denormalizationContext: ["groups" => [self::UPDATE_GROUP]],
    validationContext: ["groups" => [self::UPDATE_GROUP]]
)]
class CustomerRegistration
{
    public const READ_GROUP = "customer_registration:read";
    public const UPDATE_GROUP = "customer_registration:update";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'customerRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([self::READ_GROUP])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'customerRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(enumType: RegistrationStatus::class)]
    #[Groups([
        self::READ_GROUP,
        self::UPDATE_GROUP
    ])]
    private ?RegistrationStatus $registrationStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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

    public function getRegistrationStatus(): ?RegistrationStatus
    {
        return $this->registrationStatus;
    }

    public function setRegistrationStatus(RegistrationStatus $registrationStatus): static
    {
        $this->registrationStatus = $registrationStatus;

        return $this;
    }
}