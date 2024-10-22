<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ApiResource()]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, Recipient>
     */
    #[ORM\OneToMany(targetEntity: Recipient::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $recipients;

    /**
     * @var Collection<int, Ecounter>
     */
    #[ORM\OneToMany(targetEntity: Ecounter::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $ecounters;

    /**
     * @var Collection<int, TeamSponsor>
     */
    #[ORM\OneToMany(targetEntity: TeamSponsor::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $teamSponsors;

    /**
     * @var Collection<int, Belong>
     */
    #[ORM\OneToMany(targetEntity: Belong::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $belongs;


    public function __construct()
    {
        $this->recipients = new ArrayCollection();
        $this->ecounters = new ArrayCollection();
        $this->teamSponsors = new ArrayCollection();
        $this->belongs = new ArrayCollection();
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
     * @return Collection<int, Ecounter>
     */
    public function getEcounters(): Collection
    {
        return $this->ecounters;
    }

    public function addEcounter(Ecounter $ecounter): static
    {
        if (!$this->ecounters->contains($ecounter)) {
            $this->ecounters->add($ecounter);
            $ecounter->setTeam($this);
        }

        return $this;
    }

    public function removeEcounter(Ecounter $ecounter): static
    {
        if ($this->ecounters->removeElement($ecounter)) {
            // set the owning side to null (unless already changed)
            if ($ecounter->getTeam() === $this) {
                $ecounter->setTeam(null);
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
     * @return Collection<int, Belong>
     */
    public function getBelongs(): Collection
    {
        return $this->belongs;
    }

    public function addBelong(Belong $belong): static
    {
        if (!$this->belongs->contains($belong)) {
            $this->belongs->add($belong);
            $belong->setTeam($this);
        }

        return $this;
    }

    public function removeBelong(Belong $belong): static
    {
        if ($this->belongs->removeElement($belong)) {
            // set the owning side to null (unless already changed)
            if ($belong->getTeam() === $this) {
                $belong->setTeam(null);
            }
        }

        return $this;
    }
}
