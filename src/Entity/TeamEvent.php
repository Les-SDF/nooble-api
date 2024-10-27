<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\SaucisseType;
use App\Repository\TeamEventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeamEventRepository::class)]
#[ApiResource]
class TeamEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teamEvents')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["teams:read"])]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'teamEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(enumType: SaucisseType::class)]
    #[Groups(["teams:read"])]
    private ?SaucisseType $saucisse = null;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get the value of saucisse
     */
    public function getSaucisse()
    {
        return $this->saucisse;
    }

    /**
     * Set the value of saucisse
     *
     * @return  self
     */
    public function setSaucisse($saucisse)
    {
        $this->saucisse = $saucisse;

        return $this;
    }
}
