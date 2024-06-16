<?php

namespace App\Entity;

use App\Repository\UserInstagramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserInstagramRepository::class)]
class UserInstagram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'userInstagram', cascade: ['persist', 'remove'])]
    #[Groups(['normal', 'search'])]
    private ?User $user = null;

    #[ORM\Column(type: 'bigint', nullable: true)]
    #[Groups(['normal', 'detailed', 'search'])]
    private ?string $instagramId = null;

    #[ORM\Column(length: 255)]
    #[Groups(['search'])]
    private ?string $username = null;

    #[ORM\Column(nullable: false)]
    #[Groups(['search'])]
    private int $followersCount;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: UserInstagramCategory::class)]
    #[Groups(['detailed'])]
    private Collection $userInstagramCategories;

    #[ORM\OneToMany(mappedBy: 'userInstagram', targetEntity: Bid::class)]
    #[Groups(['search'])]
    private Collection $bids;

    public function __construct()
    {
        $this->userInstagramCategories = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }

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

    public function getInstagramId(): ?int
    {
        return $this->instagramId;
    }

    public function setInstagramId(?int $instagramId): static
    {
        $this->instagramId = $instagramId;

        return $this;
    }

    public function getFollowersCount(): int
    {
        return $this->followersCount;
    }

    public function setFollowersCount(int $followersCount): static
    {
        $this->followersCount = $followersCount;

        return $this;
    }

    /**
     * @return Collection<int, UserInstagramCategory>
     */
    public function getUserInstagramCategories(): Collection
    {
        return $this->userInstagramCategories;
    }

    public function addUserInstagramCategory(UserInstagramCategory $userInstagramCategory): static
    {
        if (!$this->userInstagramCategories->contains($userInstagramCategory)) {
            $this->userInstagramCategories->add($userInstagramCategory);
            $userInstagramCategory->setUser($this);
        }

        return $this;
    }

    public function removeUserInstagramCategory(UserInstagramCategory $userInstagramCategory): static
    {
        if ($this->userInstagramCategories->removeElement($userInstagramCategory)) {
            // set the owning side to null (unless already changed)
            if ($userInstagramCategory->getUser() === $this) {
                $userInstagramCategory->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): static
    {
        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
            $bid->setUserInstagram($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): static
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getUserInstagram() === $this) {
                $bid->setUserInstagram(null);
            }
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
