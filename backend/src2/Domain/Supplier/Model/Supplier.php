<?php

namespace Panthir\Domain\Supplier\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Panthir\Domain\Common\Model\AbstractPerson;
use Panthir\Domain\Common\Model\CountableTrait;
use Panthir\Infrastructure\Repository\Person\SupplierRepository;

#[Entity(repositoryClass: SupplierRepository::class)]
#[ORM\Table(name: 'person')]
final class Supplier extends AbstractPerson
{
    use CountableTrait;
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Column(name: 'surname')]
    private string $nickName;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: SupplierAddress::class, cascade: ['persist'])]
    private Collection $addresses;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: SupplierContact::class, cascade: ['persist'])]
    private Collection $contacts;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getNickName(): string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function setAddresses(Collection $addresses): self
    {
        $this->addresses = $addresses;

        return $this;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function setContacts(Collection $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function addAddresses(SupplierAddress $address): self
    {
        if (!$this->addresses->contains($address)) {
            $address->setPerson($this);
            $this->addresses->add($address);
        }

        return $this;
    }

    public function removeAddresses(SupplierAddress $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
        }

        return $this;
    }

    public function addContacts(SupplierContact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $contact->setPerson($this);
            $this->contacts->add($contact);
        }

        return $this;
    }

    public function removeContacts(SupplierContact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }
}
