<?php

namespace Panthir\Domain\Common\Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'person_address')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discriminator', type: 'string')]
abstract class AbstractAddress
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue('NONE')]
    protected string $id;

    #[ORM\Column]
    protected string $country;

    #[ORM\Column]
    protected string $district;

    #[ORM\Column]
    protected string $city;

    #[ORM\Column]
    protected string $address;

    #[ORM\Column]
    protected string $number;

    #[ORM\Column]
    protected string $zip;

    #[ORM\Column(nullable: true)]
    protected ?string $addressComplement;

    protected readonly UuidInterface $uuid;

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return $this
     */
    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;
        $this->id = $uuid->toString();

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    /**
     * @return $this
     */
    public function setDistrict(string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return $this
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * @return $this
     */
    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getAddressComplement(): ?string
    {
        return $this->addressComplement;
    }

    /**
     * @return $this
     */
    public function setAddressComplement(?string $addressComplement): self
    {
        $this->addressComplement = $addressComplement;

        return $this;
    }
}
