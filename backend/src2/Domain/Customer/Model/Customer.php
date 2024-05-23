<?php

namespace Panthir\Domain\Customer\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Panthir\Domain\Common\Model\AbstractPerson;
use Panthir\Domain\Common\Model\CountableTrait;
use Panthir\Infrastructure\Repository\Person\CustomerRepository;

#[Entity(repositoryClass: CustomerRepository::class)]
#[ORM\Table(name: 'person')]
final class Customer extends AbstractPerson
{
    use CountableTrait;
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Column(name: 'surname')]
    private string $surname;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: CustomerAddress::class, cascade: ['persist'])]
    private Collection $addresses;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: CustomerContact::class, cascade: ['persist'])]
    private Collection $contacts;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return $this
     */
    public function setSurname(string $surname): Customer
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return $this
     */
    public function setBirthDate(?\DateTimeInterface $birthDate): Customer
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getBirthDate(): ?string
    {
        if (empty($this->birthDate)) {
            return null;
        }

        return date_format($this->birthDate, 'd/m/Y');
    }

    public function getRawBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    /**
     * @return $this
     */
    public function addAddresses(CustomerAddress $address): self
    {
        if (!$this->addresses->contains($address)) {
            $address->setPerson($this);
            $this->addresses->add($address);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeAddresses(CustomerAddress $address): self
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

    /**
     * @return $this
     */
    public function addContacts(CustomerContact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $contact->setPerson($this);
            $this->contacts->add($contact);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeContacts(CustomerContact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }
}
