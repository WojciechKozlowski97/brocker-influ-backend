<?php

namespace App\Entity;

use App\Repository\UserInstagramCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserInstagramCategoryRepository::class)]
class UserInstagramCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userInstagramCategories')]
    private ?UserInstagram $User = null;

    #[ORM\ManyToOne(inversedBy: 'userInstagramCategories')]
    #[Groups(['detailed'])]
    private ?Category $Category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?UserInstagram
    {
        return $this->User;
    }

    public function setUser(?UserInstagram $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): static
    {
        $this->Category = $Category;

        return $this;
    }
}
