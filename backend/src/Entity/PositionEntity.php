<?php

namespace App\Entity;

use App\Repository\PositionEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Attributes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PositionEntityRepository::class)]
class PositionEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Attributes::class)]
    private Collection $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function setAttributes(Collection $attributes): static
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function addAttribute(Attributes $attribute): static {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
        }

        return $this;
    }

    public function removeAttribute(Attributes $attribute): static {
        $this->attributes->removeElement($attribute);

        return $this;
    }
}
