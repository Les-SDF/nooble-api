<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
#[ApiResource]
class Manager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'managers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\ManyToOne(inversedBy: 'managers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $events = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getEvents(): ?Event
    {
        return $this->events;
    }

    public function setEvents(?Event $events): static
    {
        $this->events = $events;

        return $this;
    }
}
