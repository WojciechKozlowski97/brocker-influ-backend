<?php

namespace App\Entity;

use App\Repository\DealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DealRepository::class)]
class Deal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['normal', 'detailed'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'deals')]
    private ?Bid $bid = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['normal', 'detailed'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Groups(['normal', 'detailed'])]
    private ?string $price = null;

    #[ORM\Column(nullable: false)]
    #[Groups(['normal', 'detailed'])]
    private int $deliveryDays;

    #[ORM\Column(nullable: false)]
    #[Groups(['normal', 'detailed'])]
    private int $revisions;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBid(): ?Bid
    {
        return $this->bid;
    }

    public function setBid(?Bid $bid): static
    {
        $this->bid = $bid;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDeliveryDays(): int
    {
        return $this->deliveryDays;
    }

    public function setDeliveryDays(int $deliveryDays): static
    {
        $this->deliveryDays = $deliveryDays;

        return $this;
    }

    public function getRevisions(): int
    {
        return $this->revisions;
    }

    public function setRevisions(int $revisions): static
    {
        $this->revisions = $revisions;

        return $this;
    }
}
