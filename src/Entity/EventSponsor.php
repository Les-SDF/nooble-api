<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\EventSponsorRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventSponsorRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/sponsor/{id}/eventSponsors",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "eventSponsors",
                    fromClass: Sponsor::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/event/{id}/eventSponsors",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "eventSponsors",
                    fromClass: Event::class
                )
            ]
        )
    ]
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
