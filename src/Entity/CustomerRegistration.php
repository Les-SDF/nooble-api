<?php

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
    denormalizationContext: ["groups" => ["customer-registration:update"]],
    validationContext: ["groups" => ["customer-registration:update"]]
)]
class CustomerRegistration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'customerRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["customer-registration:read"])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'customerRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(enumType: RegistrationStatus::class)]
    #[Groups(["customer-registration:read", "customer-registration:update"])]
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