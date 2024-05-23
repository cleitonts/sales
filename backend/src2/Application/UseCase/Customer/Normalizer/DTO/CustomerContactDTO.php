<?php

namespace Panthir\Application\UseCase\Customer\Normalizer\DTO;

use Panthir\Domain\Customer\ValueObject\ContactType;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;
use Symfony\Component\Validator\Constraints as Assert;

class CustomerContactDTO
{
    private ?string $id = null;

    #[Assert\NotBlank]
    private string $name;

    #[Assert\NotBlank]
    private string $email;

    #[Assert\NotBlank]
    private string $phone;

    #[Assert\NotBlank]
    private string $type;

    private ?bool $delete = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): CustomerContactDTO
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CustomerContactDTO
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): CustomerContactDTO
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): CustomerContactDTO
    {
        $this->phone = $phone;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /** @throws InvalidFieldException */
    public function setType(string $type): self
    {
        $enum = ContactType::tryFrom($type);
        if (!$enum) {
            throw new InvalidFieldException("Invalid type from customer's contact", 400);
        }

        $this->type = $enum->value;

        return $this;
    }

    public function getDelete(): ?bool
    {
        return $this->delete;
    }

    /**
     * @return $this
     */
    public function setDelete(?bool $delete): CustomerContactDTO
    {
        $this->delete = $delete;

        return $this;
    }
}
