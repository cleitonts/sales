<?php

namespace Panthir\Application\UseCase\Customer\Normalizer\DTO;

use Panthir\Domain\Customer\ValueObject\AddressType;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;
use Symfony\Component\Validator\Constraints as Assert;

class CustomerAddressDTO
{
    private ?string $id = null;

    #[Assert\NotBlank]
    private string $country;

    #[Assert\NotBlank]
    private string $district;

    #[Assert\NotBlank]
    private string $city;

    #[Assert\NotBlank]
    private string $address;

    #[Assert\NotBlank]
    private string $number;

    #[Assert\NotBlank]
    private string $zip;

    #[Assert\NotBlank]
    private string $type;

    private ?string $addressComplement = null;

    private ?bool $delete = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function setId(?string $id): CustomerAddressDTO
    {
        $this->id = $id;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): CustomerAddressDTO
    {
        $this->country = $country;

        return $this;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    public function setDistrict(string $district): CustomerAddressDTO
    {
        $this->district = $district;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): CustomerAddressDTO
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): CustomerAddressDTO
    {
        $this->address = $address;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): CustomerAddressDTO
    {
        $this->number = $number;

        return $this;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip): CustomerAddressDTO
    {
        $this->zip = $zip;

        return $this;
    }

    public function getAddressComplement(): ?string
    {
        return $this->addressComplement;
    }

    public function setAddressComplement(?string $addressComplement): CustomerAddressDTO
    {
        $this->addressComplement = $addressComplement;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /** @throws InvalidFieldException */
    public function setType(string $type): self
    {
        $enum = AddressType::tryFrom($type);
        if (!$enum) {
            throw new InvalidFieldException("Invalid type from customer's address", 400);
        }

        $this->type = $enum->value;

        return $this;
    }

    public function getDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(?bool $delete): CustomerAddressDTO
    {
        $this->delete = $delete;

        return $this;
    }
}
