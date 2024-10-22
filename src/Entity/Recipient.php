<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecipientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipientRepository::class)]
#[ApiResource]
class Recipient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'recipients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventReward $eventReward = null;

    #[ORM\ManyToOne(inversedBy: 'recipients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventReward(): ?EventReward
    {
        return $this->eventReward;
    }

    public function setEventReward(?EventReward $eventReward): static
    {
        $this->eventReward = $eventReward;

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
