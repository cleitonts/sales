<?php

namespace Panthir\Application\UseCase\Financial\Normalizer\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierAddressDTO;
use Panthir\Domain\Common\Model\AbstractPerson;
use Panthir\Domain\Supplier\ValueObject\ContactType;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;
use Symfony\Component\Validator\Constraints as Assert;

class TitleCreateDTO
{
    private ?string $id = null;

    #[Assert\NotBlank]
    private AbstractPerson $customer;

    private Collection $installments;

    public function __construct()
    {
        $this->installments = new ArrayCollection();
    }

    public function addInstallments(SupplierAddressDTO $address): self
    {
        if (!$this->installments->contains($address)) {
            $this->installments->add($address);
        }

        return $this;
    }

    public function setInstallments(ArrayCollection $installments): self
    {
        $this->installments = $installments;

        return $this;
    }

    public function removeInstallments(SupplierAddressDTO $address): self
    {
        if ($this->installments->contains($address)) {
            $this->installments->removeElement($address);
        }

        return $this;
    }

    public function getInstallments(): Collection
    {
        return $this->installments;
    }

    public function getCustomer(): AbstractPerson
    {
        return $this->customer;
    }

    /** @throws InvalidFieldException */
    public function setCustomer(string $customer): self
    {
        $enum = ContactType::tryFrom($customer);
        if (!$enum) {
            throw new InvalidFieldException("Invalid type from supplier's contact.", 400);
        }

        $this->type = $enum->value;

        return $this;
    }
}
