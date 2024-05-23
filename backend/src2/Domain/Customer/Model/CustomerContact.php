<?php

namespace Panthir\Domain\Customer\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Panthir\Domain\Common\Model\AbstractContact;
use Panthir\Domain\Customer\ValueObject\ContactType;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;

#[ORM\Entity]
#[ORM\Table(name: 'person_contact')]
final class CustomerContact extends AbstractContact
{
    #[ORM\Column(name: 'type')]
    private string $type;

    #[ManyToOne(targetEntity: Customer::class, inversedBy: 'contacts')]
    #[JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    private Customer $person;

    public function getPerson(): Customer
    {
        return $this->person;
    }

    public function setPerson(Customer $person): CustomerContact
    {
        $this->person = $person;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return $this
     *
     * @throws InvalidFieldException
     */
    public function setType(string $type): self
    {
        $enum = ContactType::tryFrom($type);
        if (!$enum) {
            throw new InvalidFieldException("Invalid type from customer's contact", 400);
        }

        $this->type = $enum->value;

        return $this;
    }
}
