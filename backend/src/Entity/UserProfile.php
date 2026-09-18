<?php

namespace App\Entity;

use App\DTO\MeDto;
use App\Repository\UserProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use App\DTO\UpdateProfileDto;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\OneToOne(inversedBy: 'profile', targetEntity: User::class)]
    #[ORM\JoinColumn('user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Ignore]
    private ?User $user = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarUrl = null;


    #[ORM\Column(type: Types::INTEGER, options: ['default' => 1])]
    #[ORM\Version]
    private int $version = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function setUser(User $user): self{
        $this->user = $user;

        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): static
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
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

    public function updateFromDto(MeDto $meDto): self{
        if ($meDto->firstName !== null){
            $this->setFirstName($meDto->firstName);
        }
        if ($meDto->lastName !== null){
            $this->setLastName($meDto->lastName);
        }
        if ($meDto->location !== null){
            $this->setLocation($meDto->location);
        }
        if ($meDto->avatarUrl !== null){
            $this->setAvatarUrl($meDto->avatarUrl);
        }
        return $this;
    }
}
