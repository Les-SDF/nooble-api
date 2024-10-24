<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use App\Enum\SaucisseType;
use App\Repository\RegisterRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegisterRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/users/{id}/registers",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "registers",
                    fromClass: User::class
                )
            ]
        ),
        new GetCollection(
            uriTemplate: "/event/{id}/registers",
            uriVariables: [
                "id" => new Link(
                    fromProperty: "registers",
                    fromClass: Event::class
                )
            ]
        ),
        new Patch(
            denormalizationContext: ["groups" => ["register:update"]],
            // security: "is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object == user)",
            validationContext: ["groups" => ["register:update"]],
        )
    ]
)]
class Register
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'registers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["register:read"])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'registers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(enumType: SaucisseType::class)]
    #[Groups(["register:read", "register:update"])]
    private ?SaucisseType $saucisse = null;

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
