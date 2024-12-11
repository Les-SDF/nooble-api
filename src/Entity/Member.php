<?php

/**
 * @noinspection NestedPositiveIfStatementsInspection
 * @noinspection PhpUnused
 */

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Enum\MemberStatus;
use App\Repository\MemberRepository;
use App\State\MemberProcessor;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Link;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\UniqueConstraint(columns: ['user_id', 'team_id'])]
#[UniqueEntity(fields: ['user', 'team'])]
#[ApiResource]
#[GetCollection(
    uriTemplate: "/teams/{id}/members",
    uriVariables: [
        "id" => new Link(
            fromProperty: "members",
            fromClass: Team::class,
        )
    ]
)]
#[Get]
#[Post(
    denormalizationContext: ["groups" => [self::CREATE_GROUP]],
    security: "is_granted('MEMBER_CREATE', object)",
    processor: MemberProcessor::class,
)]
#[Patch(
    security: "is_granted('MEMBER_UPDATE', object)"
)]
#[Delete(
    security: "is_granted('MEMBER_DELETE', object)"
)]
class Member
{
    public const CREATE_GROUP = "member:create";
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        self::CREATE_GROUP,
        Confrontation::READ_GROUP,
        Confrontation::READ_COLLECTION_GROUP,
    ])]
    private ?User $user = null;

    #[ORM\ManyToOne(
        cascade: ['persist'],
        inversedBy: 'members'
    )]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        self::CREATE_GROUP,
        CustomerRegistration::READ_GROUP,
        User::READ_GROUP,
    ])]
    private ?Team $team = null;

    #[ORM\Column(enumType: MemberStatus::class, options: ["default" => MemberStatus::Pending])]
    private ?MemberStatus $status = MemberStatus::Pending;

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

    public function getStatus(): ?MemberStatus
    {
        return $this->status;
    }

    public function setStatus(MemberStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
