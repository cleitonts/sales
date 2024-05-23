<?php

namespace Panthir\Domain\User\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Panthir\Domain\Common\Model\CountableTrait;
use Panthir\Domain\User\ValueObject\UserRoles;
use Panthir\Infrastructure\Repository\User\UserRepository;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use CountableTrait;
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue('NONE')]
    private string $id;

    public function __construct(
        protected UuidInterface $uuid,

        #[ORM\Column(length: 180, unique: true)]
        private string $email,

        #[ORM\Column]
        private array $roles,

        #[ORM\Column(type: 'text', length: 10000, unique: true, nullable: true)]
        private ?string $passwordResetToken,

        #[ORM\Column]
        private ?string $password,
    ) {
        $this->id = $uuid->__toString();
    }

    public function getProfile(): string
    {
        return UserRoles::getProfileByRoles($this->getRoles());
    }

    public function setProfile(string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken(?string $passwordResetToken): User
    {
        $this->passwordResetToken = $passwordResetToken;

        return $this;
    }
}
