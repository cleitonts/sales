<?php

declare(strict_types=1);

namespace App\Core\Domain\Model\User;

use App\Shared\Domain\Model\Aggregate;
use App\Shared\Domain\Service\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User extends Aggregate implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const DEFAULT_USER_ROLE = 'ROLE_USER';
    public const MAX_USER_NAME_LENGTH = 180;
    public const MAX_PASSWORD_LENGTH = 255;

    #[ORM\Id, ORM\Column(type: 'string', options: ['unsigned' => true])]
    private string $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $username;

    /**
     * @var array<int, string>
     */
    #[ORM\Column(type: 'json', nullable: false)]
    private array $roles = [];

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'datetime_immutable', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    /**
     * @param array|string[] $roles
     */
    public function __construct(
        string $uuid,
        string $username,
        array $roles = [self::DEFAULT_USER_ROLE]
    ) {
        $this->id = $uuid;
        $this->setUsername($username);
        $this->setRoles($roles);
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function eraseCredentials(): void
    {
        // dont need
    }

    public function equals(User $user): bool
    {
        return $user->getId() === $this->getId();
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return '';
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    // Setters

    public function setPassword(string $password): void
    {
        Assert::maxLength($password, self::MAX_PASSWORD_LENGTH, 'Password should contain at most %2$s characters. Got: %s');
        $this->password = $password;
    }

    private function setUsername(string $username): void
    {
        Assert::maxLength($username, self::MAX_USER_NAME_LENGTH, 'Username should contain at most %2$s characters. Got: %s');
        $this->username = $username;
    }

    private function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param array<int, string> $roles
     */
    private function setRoles(array $roles): void
    {
        if (!\in_array(self::DEFAULT_USER_ROLE, $roles, true)) {
            $roles[] = self::DEFAULT_USER_ROLE;
        }

        $this->roles = array_unique($roles);
    }
}
