<?php

namespace Panthir\Application\UseCase\Customer\Normalizer\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class CustomerCreateDTO
{
    private ?string $id = null;

    private Collection $addresses;

    private Collection $contacts;

    #[Assert\NotBlank]
    private string $name;

    #[Assert\NotBlank]
    private string $surname;

    #[Assert\NotBlank]
    private string $document;

    private ?\DateTime $birthDate = null;

    private ?string $secondaryDocument = null;

    private ?string $additionalInformation = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): CustomerCreateDTO
    {
        $this->id = $id;

        return $this;
    }

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function setAddresses(Collection $addresses): CustomerCreateDTO
    {
        $this->addresses = $addresses;

        return $this;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function setContacts(Collection $contacts): CustomerCreateDTO
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function addAddresses(CustomerAddressDTO $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
        }

        return $this;
    }

    public function removeAddresses(CustomerAddressDTO $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
        }

        return $this;
    }

    public function addContacts(CustomerContactDTO $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
        }

        return $this;
    }

    public function removeContacts(CustomerContactDTO $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    public function getBirthDate(): string
    {
        if (empty($this->birthDate)) {
            return '';
        }

        return date_format($this->birthDate, 'd/m/Y');
    }

    public function getRawBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function getSecondaryDocument(): ?string
    {
        return $this->secondaryDocument;
    }

    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    public function setName(string $name): CustomerCreateDTO
    {
        $this->name = $name;

        return $this;
    }

    public function setSurname(string $surname): CustomerCreateDTO
    {
        $this->surname = $surname;

        return $this;
    }

    public function setDocument(string $document): CustomerCreateDTO
    {
        $this->document = $document;

        return $this;
    }

    public function setBirthDate(?\DateTime $birthDate): CustomerCreateDTO
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function setSecondaryDocument(?string $secondaryDocument): CustomerCreateDTO
    {
        $this->secondaryDocument = $secondaryDocument;

        return $this;
    }

    public function setAdditionalInformation(?string $additionalInformation): CustomerCreateDTO
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }
}
