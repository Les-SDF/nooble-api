<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\EventSponsorRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventSponsorRepository::class)]
#[ApiResource]
#[GetCollection(
    uriTemplate: "/sponsors/{id}/event_sponsors",
    uriVariables: [
        "id" => new Link(
            fromProperty: "eventSponsors",
            fromClass: Sponsor::class
        )
    ]
)]
#[GetCollection(
    uriTemplate: "/events/{id}/event_sponsors",
    uriVariables: [
        "id" => new Link(
            fromProperty: "eventSponsors",
            fromClass: Event::class
        )
    ]
)]
#[Get]
#[Post(
    security: "is_granted('EVENT_SPONSOR_CREATE', object)"
)]
#[Delete(
    security: "is_granted('EVENT_SPONSOR_DELETE', object)"
)]
class EventSponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventSponsors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'eventSponsors')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["event:read"])]
    private ?Sponsor $sponsor = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSponsor(): ?Sponsor
    {
        return $this->sponsor;
    }

    public function setSponsor(?Sponsor $sponsor): static
    {
        $this->sponsor = $sponsor;

        return $this;
    }
}