<?php

namespace App\Entity;

use App\Repository\BidRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BidRepository::class)]
class Bid
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['normal', 'detailed', 'search'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[Groups(['normal'])]
    private ?UserInstagram $userInstagram = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[Groups(['normal', 'detailed'])]
    private ?Site $site = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['normal', 'detailed', 'search'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['detailed'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    #[Groups(['normal', 'detailed', 'search'])]
    private int $price;

    #[ORM\OneToMany(mappedBy: 'bid', targetEntity: Deal::class)]
    #[Groups(['detailed'])]
    private Collection $deals;

    #[ORM\Column]
    #[Groups(['normal', 'detailed'])]
    private ?bool $isDeleted = null;

    #[ORM\OneToMany(mappedBy: 'bid', targetEntity: BidImage::class)]
    #[Groups(['detailed'])]
    private Collection $bidImages;

    public function __construct()
    {
        $this->deals = new ArrayCollection();
        $this->bidImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserInstagram(): ?UserInstagram
    {
        return $this->userInstagram;
    }

    public function setUserInstagram(?UserInstagram $userInstagram): static
    {
        $this->userInstagram = $userInstagram;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Deal>
     */
    public function getDeals(): Collection
    {
        return $this->deals;
    }

    public function addDeal(Deal $deal): static
    {
        if (!$this->deals->contains($deal)) {
            $this->deals->add($deal);
            $deal->setBid($this);
        }

        return $this;
    }

    public function removeDeal(Deal $deal): static
    {
        if ($this->deals->removeElement($deal)) {
            // set the owning side to null (unless already changed)
            if ($deal->getBid() === $this) {
                $deal->setBid(null);
            }
        }

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection<int, BidImage>
     */
    public function getBidImages(): Collection
    {
        return $this->bidImages;
    }

    public function addBidImage(BidImage $bidImage): static
    {
        if (!$this->bidImages->contains($bidImage)) {
            $this->bidImages->add($bidImage);
            $bidImage->setBid($this);
        }

        return $this;
    }

    public function removeBidImage(BidImage $bidImage): static
    {
        if ($this->bidImages->removeElement($bidImage)) {
            // set the owning side to null (unless already changed)
            if ($bidImage->getBid() === $this) {
                $bidImage->setBid(null);
            }
        }

        return $this;
    }
}
