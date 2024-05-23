<?php

namespace Panthir\Domain\Customer\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Panthir\Domain\Common\Model\AbstractAddress;
use Panthir\Domain\Customer\ValueObject\AddressType;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;

#[ORM\Entity]
#[ORM\Table(name: 'person_address')]
final class CustomerAddress extends AbstractAddress
{
    #[ORM\Column(name: 'type')]
    private string $type;

    #[ManyToOne(targetEntity: Customer::class, inversedBy: 'addresses')]
    #[JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    private Customer $person;

    public function getPerson(): Customer
    {
        return $this->person;
    }

    public function setPerson(Customer $person): CustomerAddress
    {
        $this->person = $person;

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
}
