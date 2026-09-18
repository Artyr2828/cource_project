<?php

namespace App\Entity;

use App\Repository\UserAttributeRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints\Cascade;
use App\Entity\Attributes;
use App\Enums\AttributeType;

#[ORM\Entity(repositoryClass: UserAttributeRepository::class)]
class UserAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'attributes', targetEntity: User::class)]
    #[JoinColumn('user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Ignore]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userAttributes', targetEntity: Attributes::class)]
    #[ORM\JoinColumn('attribute_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Attributes $attribute = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAttribute(): ?Attributes
    {
        return $this->attribute;
    }

    public function setAttribute(Attributes $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getValue()
    {
        if ($this->attribute->getType() === AttributeType::PERIOD){
            $json = json_decode($this->value);
            if ($json !== null){
                return json_decode($this->value);
            } else{
                $jsonStr = json_encode([
                    'from' => '',
                    'to' => ''
                ]);
                return json_decode($jsonStr);
               
             }
        }
        if ($this->attribute->getType() === AttributeType::BOOLEAN){
            if ($this->value === ''){
                return false;
            } else if ($this->value === '1'){
                return true;
            }
        }
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }
}
