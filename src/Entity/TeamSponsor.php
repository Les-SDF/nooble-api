<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use App\Repository\TeamSponsorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamSponsorRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/teams/{id}/sponsors",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "teamSponsors",
                    fromClass: Team::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/sponsor/{id}/teamSponsors",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "teamSponsors",
                    fromClass: Sponsor::class
                )
            ]
        ),
        new Get(),
        new Post(),
        new Delete()
    ]
)]
class TeamSponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teamSponsors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'teamSponsors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sponsor $sponsor = null;

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

    public function getSponsor(): ?Sponsor
    {
        return $this->sponsor;
    }

    public function setSponsor(?Sponsor $sponsor): static
    {
        $this->sponsor = $sponsor;

        return $this;
    }
}
