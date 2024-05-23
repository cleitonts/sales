<?php

namespace Panthir\Application\UseCase\Supplier\Normalizer\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class SupplierCreateDTO
{
    private Collection $addresses;

    private Collection $contacts;

    #[Assert\NotBlank]
    private string $name;

    #[Assert\NotBlank]
    private string $nickName;

    #[Assert\NotBlank]
    private string $document;

    private ?string $secondaryDocument = null;

    private ?string $id = null;

    private ?string $additionalInformation = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SupplierCreateDTO
    {
        $this->name = $name;

        return $this;
    }

    public function getNickName(): string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): SupplierCreateDTO
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function setDocument(string $document): SupplierCreateDTO
    {
        $this->document = $document;

        return $this;
    }

    public function getSecondaryDocument(): ?string
    {
        return $this->secondaryDocument;
    }

    public function setSecondaryDocument(?string $secondaryDocument): SupplierCreateDTO
    {
        $this->secondaryDocument = $secondaryDocument;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): SupplierCreateDTO
    {
        $this->id = $id;

        return $this;
    }

    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?string $additionalInformation): SupplierCreateDTO
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    public function addContacts(SupplierContactDTO $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
        }

        return $this;
    }

    public function removeContacts(SupplierContactDTO $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    public function addAddresses(SupplierAddressDTO $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
        }

        return $this;
    }

    public function removeAddresses(SupplierAddressDTO $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
        }

        return $this;
    }

    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function setAddresses(ArrayCollection $addresses): self
    {
        $this->addresses = $addresses;

        return $this;
    }

    public function setContacts(ArrayCollection $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }
}
