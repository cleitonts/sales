<?php

namespace Panthir\Application\UseCase\Supplier\Normalizer\DTO;

use Panthir\Domain\Supplier\ValueObject\ContactType;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;
use Symfony\Component\Validator\Constraints as Assert;

class SupplierContactDTO
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

    public function setId(?string $id): SupplierContactDTO
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SupplierContactDTO
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): SupplierContactDTO
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): SupplierContactDTO
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(?bool $delete): SupplierContactDTO
    {
        $this->delete = $delete;

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
            throw new InvalidFieldException("Invalid type from supplier's contact.", 400);
        }

        $this->type = $enum->value;

        return $this;
    }
}
