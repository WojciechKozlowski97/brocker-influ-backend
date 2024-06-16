<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'Category', targetEntity: UserInstagramCategory::class)]
    private Collection $userInstagramCategories;

    public function __construct()
    {
        $this->userInstagramCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $userInstagramCategory->setCategory($this);
        }

        return $this;
    }

    public function removeUserInstagramCategory(UserInstagramCategory $userInstagramCategory): static
    {
        if ($this->userInstagramCategories->removeElement($userInstagramCategory)) {
            // set the owning side to null (unless already changed)
            if ($userInstagramCategory->getCategory() === $this) {
                $userInstagramCategory->setCategory(null);
            }
        }

        return $this;
    }
}
