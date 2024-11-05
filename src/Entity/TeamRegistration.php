<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\RegistrationStatus;
use App\Repository\TeamRegistrationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeamRegistrationRepository::class)]
#[ORM\UniqueConstraint(columns: ["team_id", "event_id"])]
#[ApiResource]
#[GetCollection(
    uriTemplate: "/teams/{id}/events",
    uriVariables: [
        "id" => new Link(
            fromProperty: "teamRegistrations",
            fromClass: Team::class
        )
    ]
)]
#[GetCollection(
    uriTemplate: "/events/{id}/teams",
    uriVariables: [
        "id" => new Link(
            fromProperty: "teamRegistrations",
            fromClass: Event::class
        ),
    ],
)]
#[Get]
#[Post(
/**
 * https://api-platform.com/docs/core/security/#hooking-custom-permission-checks-using-voters
 */
    securityPostDenormalize: "is_granted('TEAM_REGISTRATION_CREATE', object)",
)]
#[Patch]
#[Delete]
class TeamRegistration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teamRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["event:read", "team:read", "teams:read"])]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'teamRegistrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(enumType: RegistrationStatus::class, options: ["default" => RegistrationStatus::Waiting])]
    #[Groups(["team:read", "teams:read"])]
    private ?RegistrationStatus $registrationStatus = RegistrationStatus::Waiting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

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