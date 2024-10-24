<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\BelongRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Link;

#[ORM\Entity(repositoryClass: BelongRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/users/{id}/belong",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "belongs",
                    fromClass: User::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/teams/{id}/belong",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "belongs",
                    fromClass: Team::class
                )
            ]
        )
    ]
)]
class Belong
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'belongs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["participations:read", "participation:read"])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'belongs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["user:read", "register:read"])]
    private ?Team $team = null;

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

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }
}
