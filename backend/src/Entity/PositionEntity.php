<?php

namespace App\Entity;

use App\DTO\PositionUpdatedDto;
use App\Repository\PositionEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Attributes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 1])]
    #[ORM\Version]
    private int $version = 1;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private \DateTimeImmutable $created_at;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        
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

       public function getVersion(): ?int {
        return $this->version;
        }

    public function setVersion(int $version): static {
        $this->version = $version;

        return $this;
    }

    public function updateFromPosition(PositionUpdatedDto $positionUpdated, array $newPositionAttributeEntityes): void {
        $isUpdated = false;
        $this->updateBasicInfo($positionUpdated->name, $positionUpdated->description);
        $existingPositionAttributeIds = [];

        foreach ($this->attributes as $attribute) {
            $existingPositionAttributeIds[] = $attribute->getId();
        }
        

        $existingPositionAttributes = [];
        foreach ($this->attributes as $attribute){
            $existingPositionAttributes[] = $attribute;
        }
        
        //add
        foreach ($newPositionAttributeEntityes as $attribute){
            if (!in_array($attribute, $existingPositionAttributes)){
                $this->attributes->add($attribute);
                $isUpdated = true;
            }
        }
        
        //delete
        foreach ($existingPositionAttributes as $attributeExisting){
            if (!in_array($attributeExisting, $newPositionAttributeEntityes)){
                $this->attributes->removeElement($attributeExisting);
                $isUpdated = true;
            }
        }

        if ($isUpdated === true){
            $this->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    public function updateBasicInfo(string $name, string $description): void{
        if ($this->name !== $name) {
            $this->name = $name;
        }

        if ($this->description !== $description) {
            $this->description = $description;
        }
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
