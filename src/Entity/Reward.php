<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\RewardType;
use App\Repository\RewardRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RewardRepository::class)]
#[ApiResource()]
class Reward
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: RewardType::class)]
    private ?RewardType $rewardType = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRewardType(): ?RewardType
    {
        return $this->rewardType;
    }

    public function setRewardType(RewardType $rewardType): static
    {
        $this->rewardType = $rewardType;

        return $this;
    }
}