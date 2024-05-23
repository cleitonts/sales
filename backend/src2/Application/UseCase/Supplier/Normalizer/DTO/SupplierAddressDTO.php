<?php

namespace Panthir\Application\UseCase\Supplier\Normalizer\DTO;

use Panthir\Domain\Supplier\ValueObject\AddressType;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;
use Symfony\Component\Validator\Constraints as Assert;

class SupplierAddressDTO
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

    public function setId(?string $id): SupplierAddressDTO
    {
        $this->id = $id;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): SupplierAddressDTO
    {
        $this->country = $country;

        return $this;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    public function setDistrict(string $district): SupplierAddressDTO
    {
        $this->district = $district;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): SupplierAddressDTO
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): SupplierAddressDTO
    {
        $this->address = $address;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): SupplierAddressDTO
    {
        $this->number = $number;

        return $this;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip): SupplierAddressDTO
    {
        $this->zip = $zip;

        return $this;
    }

    public function getAddressComplement(): ?string
    {
        return $this->addressComplement;
    }

    public function setAddressComplement(?string $addressComplement): SupplierAddressDTO
    {
        $this->addressComplement = $addressComplement;

        return $this;
    }

    public function getDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(?bool $delete): SupplierAddressDTO
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
        if (!AddressType::tryFrom($type)) {
            throw new InvalidFieldException('Invalid Address type');
        }

        $this->type = $type;

        return $this;
    }
}
