<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'This email is already in use.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['normal', 'detailed', 'search'])]
    private ?int $id = null;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?string $facebookId = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Email should not be blank.')]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: "boolean")]
    private bool $isVerified = false;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserInstagram $userInstagram = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserActivation $userActivation = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?PasswordResetToken $passwordResetToken = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): static
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUserInstagram(): ?UserInstagram
    {
        return $this->userInstagram;
    }

    public function setUserInstagram(?UserInstagram $userInstagram): static
    {
        if ($userInstagram === null && $this->userInstagram !== null) {
            $this->userInstagram->setUser(null);
        }

        if ($userInstagram !== null && $userInstagram->getUser() !== $this) {
            $userInstagram->setUser($this);
        }

        $this->userInstagram = $userInstagram;

        return $this;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUserActivation(): ?UserActivation
    {
        return $this->userActivation;
    }

    public function setUserActivation(UserActivation $userActivation): static
    {
        // set the owning side of the relation if necessary
        if ($userActivation->getUser() !== $this) {
            $userActivation->setUser($this);
        }

        $this->userActivation = $userActivation;

        return $this;
    }

    public function getPasswordResetToken(): ?PasswordResetToken
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken(?PasswordResetToken $passwordResetToken): static
    {
        if ($passwordResetToken === null && $this->passwordResetToken !== null) {
            $this->passwordResetToken->setUser(null);
        }

        if ($passwordResetToken !== null && $passwordResetToken->getUser() !== $this) {
            $passwordResetToken->setUser($this);
        }

        $this->passwordResetToken = $passwordResetToken;

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
}
