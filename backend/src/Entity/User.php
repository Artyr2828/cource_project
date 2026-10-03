<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enums\UserRole;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Entity\UserProfile;
use App\Entity\UserAttribute;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Profile;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`users`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'string',
    length: 20,
    enumType: UserRole::class,
    options: ['default'=>'candidate'])]
    private UserRole $role = UserRole::CANDIDATE;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $salesforceAccountId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $salesforceContactId = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Profile::class)]
    private ?Profile $profile = null;   

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    public function getRole(): ?string
    {
        return $this->role->value;
    }

    public function setRole(UserRole $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void{
      $this->createdAt = new \DateTimeImmutable();
    }

    public function getUserIdentifier(): string{
      return $this->email;
    }

    public function getRoles(): array{
      $roles = ['ROLE_' . strtoupper($this->role->value)];
      $roles[] = 'ROLE_USER';
      return $roles;
    }

    public function getProfile(): Profile{
        return $this->profile;
    }

    public function setProfile(Profile $profile): static {
        $this->profile = $profile;

        return $this;
    }

    public function getSalesforceAccountId(): ?string {
        return $this->salesforceAccountId;
    }

    public function setSalesforceAccountId(?string $salesforceAccountId): self {
        $this->salesforceAccountId = $salesforceAccountId;
        return $this;
    }

    public function getSalesforceContactId(): ?string {
    return $this->salesforceContactId;
}

    public function setSalesforceContactId(?string $salesforceContactId): self {
        $this->salesforceContactId = $salesforceContactId;
        return $this;       
    }

}
