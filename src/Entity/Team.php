<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ApiResource]
#[GetCollection]
#[Get]
#[Post(
    denormalizationContext: ["groups" => ["team:create"]],
    security: "is_granted('TEAM_CREATE', object)",
)]
#[Patch(
    denormalizationContext: ["groups" => ["team:update"]],
    security: "is_granted('TEAM_UPDATE', object)",
)]
#[Delete(
    security: "is_granted('TEAM_DELETE', object)",
)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(groups: ["team:create", "team:update"])]
    #[Assert\NotNull(groups: ["team:create", "team:update"])]
    #[Assert\Length(min: 2, max: 255, minMessage: "Name must at least contains 2 characters", maxMessage: "Name must not exceed 255 characters", groups: ["team:create", "team:update"])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        "team:read",
        "team:create",
        "team:update",
        "user:read",
        "customer_registration:read",
        "confrontations:read",
        "confrontation:read",
        "event:read",
        "teams:read"
    ])]
    private ?string $name = null;

    /**
     * @var Collection<int, Recipient>
     */
    #[ORM\OneToMany(targetEntity: Recipient::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $recipients;

    /**
     * @var Collection<int, TeamSponsor>
     */
    #[ORM\OneToMany(targetEntity: TeamSponsor::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $teamSponsors;

    /**
     * @var Collection<int, Member>
     */
    #[ORM\OneToMany(targetEntity: Member::class, mappedBy: 'team', orphanRemoval: true)]
    #[Groups([
        "confrontations:read",
        "confrontation:read"
    ])]
    private Collection $members;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $participations;

    /**
     * @var Collection<int, TeamRegistration>
     */
    #[ORM\OneToMany(targetEntity: TeamRegistration::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $teamRegistrations;


    public function __construct()
    {
        $this->recipients = new ArrayCollection();
        $this->teamSponsors = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->teamRegistrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Recipient>
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    public function addRecipient(Recipient $recipient): static
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients->add($recipient);
            $recipient->setTeam($this);
        }

        return $this;
    }

    public function removeRecipient(Recipient $recipient): static
    {
        if ($this->recipients->removeElement($recipient)) {
            // set the owning side to null (unless already changed)
            if ($recipient->getTeam() === $this) {
                $recipient->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeamSponsor>
     */
    public function getTeamSponsors(): Collection
    {
        return $this->teamSponsors;
    }

    public function addTeamSponsor(TeamSponsor $teamSponsor): static
    {
        if (!$this->teamSponsors->contains($teamSponsor)) {
            $this->teamSponsors->add($teamSponsor);
            $teamSponsor->setTeam($this);
        }

        return $this;
    }

    public function removeTeamSponsor(TeamSponsor $teamSponsor): static
    {
        if ($this->teamSponsors->removeElement($teamSponsor)) {
            // set the owning side to null (unless already changed)
            if ($teamSponsor->getTeam() === $this) {
                $teamSponsor->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setTeam($this);
        }

        return $this;
    }

    public function removeMember(Member $member): static
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getTeam() === $this) {
                $member->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setTeam($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getTeam() === $this) {
                $participation->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeamRegistration>
     */
    public function getTeamRegistrations(): Collection
    {
        return $this->teamRegistrations;
    }

    public function addTeamRegistration(TeamRegistration $teamRegistration): static
    {
        if (!$this->teamRegistrations->contains($teamRegistration)) {
            $this->teamRegistrations->add($teamRegistration);
            $teamRegistration->setTeam($this);
        }

        return $this;
    }

    public function removeTeamRegistration(TeamRegistration $teamRegistration): static
    {
        if ($this->teamRegistrations->removeElement($teamRegistration)) {
            // set the owning side to null (unless already changed)
            if ($teamRegistration->getTeam() === $this) {
                $teamRegistration->setTeam(null);
            }
        }

        return $this;
    }
}