<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Doctrine\ORM\Mapping\OneToOne;
use App\Entity\UserProfile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\OneToMany;
use App\Entity\Attributes;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[OneToOne(inversedBy: 'profile', targetEntity: User::class)]
     #[ORM\JoinColumn('user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
     #[Ignore]
    private ?User $user = null;

    #[OneToOne(inversedBy: 'profile', targetEntity: UserProfile::class)]
    #[ORM\JoinColumn('me_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?UserProfile $me = null;

    #[OneToMany(mappedBy: 'profile', targetEntity: UserAttribute::class, orphanRemoval: true)]
    private Collection $attributes;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 1])]
    #[ORM\Version]
    private int $version = 1;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $created_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
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

    public function getMe(): ?UserProfile
    {
        return $this->me;
    }

    public function setMe(?UserProfile $me): static
    {
        $this->me = $me;

        return $this;
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function addToAttributes(UserAttribute $userAttribute){
        $userAttribute->setProfile($this);
        $this->attributes->add($userAttribute);
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    
    
}
