<?php

declare(strict_types=1);

namespace App\Core\Application\Query\User\DTO;

use App\Core\Domain\Model\User\User;

final class UserDTO
{
    private string $id;

    private string $username;

    private \DateTimeImmutable $createdAt;

    public static function fromEntity(User $user): UserDTO
    {
        $dto = new UserDTO();
        $dto->setId($user->getId());
        $dto->setUsername($user->getUsername());
        $dto->setCreatedAt($user->getCreatedAt());

        return $dto;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws \Exception
     */
    public static function fromQueryArray(array $data): UserDTO
    {
        if (!isset($data['id'], $data['username'], $data['createdAt'])) {
            throw new \InvalidArgumentException(sprintf('Not all keys are set or null %s', var_export($data, true)));
        }

        $dto = new UserDTO();
        $dto->setId($data['id']);
        $dto->setUsername($data['username']);
        $dto->setCreatedAt($data['createdAt']);

        return $dto;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
