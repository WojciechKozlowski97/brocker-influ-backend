<?php

namespace App\Entity;

use App\Repository\BidImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: BidImageRepository::class)]
#[Vich\Uploadable]
class BidImage
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bidImages')]
    #[Groups(['normal', 'detailed', 'search'])]
    private ?Bid $bid = null;

    #[Vich\UploadableField(mapping: 'bid_images', fileNameProperty: 'imageName')]
    #[Groups(['normal', 'detailed', 'search'])]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['normal', 'detailed', 'search'])]
    private ?string $imageName = null;

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

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
}
