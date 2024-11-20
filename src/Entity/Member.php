<?php

/**
 * @noinspection NestedPositiveIfStatementsInspection
 * @noinspection PhpUnused
 */

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\MemberRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Link;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ApiResource]
#[GetCollection(
    uriTemplate: "/teams/{id}/members",
    uriVariables: [
        "id" => new Link(
            fromProperty: "members",
            fromClass: Team::class
        )
    ]
)]
#[Get]
#[Post]
#[Delete(security: "is_granted('MEMBER_DELETE',object)")]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        Confrontation::READ_GROUP,
        Confrontation::READ_COLLECTION_GROUP,
    ])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        CustomerRegistration::READ_GROUP,
        User::READ_GROUP,
    ])]
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
