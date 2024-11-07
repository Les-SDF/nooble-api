<?php
/**
 * @noinspection NestedPositiveIfStatementsInspection
 * @noinspection PhpUnused
 */

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
#[ApiResource]
#[GetCollection(
    uriTemplate: "/teams/{id}/team_sponsors",
    uriVariables: [
        "id" => new Link(
            fromProperty: "teamSponsors",
            fromClass: Team::class
        )
    ]
)]
#[GetCollection(
    uriTemplate: "/sponsors/{id}/team_sponsors",
    uriVariables: [
        "id" => new Link(
            fromProperty: "teamSponsors",
            fromClass: Sponsor::class
        )
    ]
)]
#[Get]
#[Post]
#[Delete]
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