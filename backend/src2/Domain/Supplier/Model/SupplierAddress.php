<?php

namespace Panthir\Domain\Supplier\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Panthir\Domain\Common\Model\AbstractAddress;
use Panthir\Domain\Supplier\ValueObject\AddressType;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;

#[ORM\Entity]
#[ORM\Table(name: 'person_address')]
final class SupplierAddress extends AbstractAddress
{
    #[ORM\Column(name: 'type')]
    private string $type;

    #[ManyToOne(targetEntity: Supplier::class, inversedBy: 'addresses')]
    #[JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    private Supplier $person;

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
        $enum = AddressType::tryFrom($type);
        if (!$enum) {
            throw new InvalidFieldException("Invalid type from supplier's address", 400);
        }

        $this->type = $enum->value;

        return $this;
    }

    public function getPerson(): Supplier
    {
        return $this->person;
    }

    /**
     * @return $this
     */
    public function setPerson(Supplier $person): self
    {
        $this->person = $person;

        return $this;
    }
}
