<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\ParticipationRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
#[ApiResource]
#[GetCollection(
    uriTemplate: "/teams/{id}/participations",
    uriVariables: [
        "id" => new Link(
            fromProperty: "participations",
            fromClass: Team::class
        )
    ]
)]
#[GetCollection(
    uriTemplate: "/confrontations/{id}/participations",
    uriVariables: [
        "id" => new Link(
            fromProperty: "participations",
            fromClass: Confrontation::class
        )
    ]
)]
#[Get]
#[Post]
#[Delete]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        Confrontation::READ_GROUP,
        Confrontation::READ_COLLECTION_GROUP,
    ])]
    private ?int $ranking = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        Confrontation::READ_GROUP,
        Confrontation::READ_COLLECTION_GROUP,
    ])]
    private ?int $score = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Confrontation $confrontation = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        Confrontation::READ_GROUP,
        Confrontation::READ_COLLECTION_GROUP,
    ])]
    private ?Team $team = null;

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