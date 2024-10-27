<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/events/{id}/managers",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "managers",
                    fromClass: Event::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/users/{id}/managers",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "managers",
                    fromClass: User::class
                )
            ]
        ),
        new GetCollection(),
        new Get(),
        new Post(),
        new Delete()
    ]
)]
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