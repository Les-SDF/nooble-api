<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\EncounterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EncounterRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/teams/{id}/encounters",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "encounters",
                    fromClass: Team::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/confrontations/{id}/encounters",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "encounters",
                    fromClass: Confrontation::class
                )
            ]
        ),
        new Get(),
        new Post(
            security: "is_granted('ENCOUNTER_CREATE', object)"
        ),
        new Delete(
            security: "is_granted('ENCOUNTER_DELETE', object)"
        )
    ]
)]
class Encounter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["confrontations:read", "confrontation:read"])]
    private ?int $ranking = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["confrontations:read", "confrontation:read"])]
    private ?int $score = null;

    #[ORM\ManyToOne(inversedBy: 'encounters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Confrontation $confrontation = null;

    #[ORM\ManyToOne(inversedBy: 'encounters')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["confrontations:read", "confrontation:read"])]
    private ?Team $team = null;

    public function __construct() {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRanking(): ?int
    {
        return $this->ranking;
    }

    public function setRanking(?int $ranking): static
    {
        $this->ranking = $ranking;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getConfrontation(): ?Confrontation
    {
        return $this->confrontation;
    }

    public function setConfrontation(?Confrontation $confrontation): static
    {
        $this->confrontation = $confrontation;

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