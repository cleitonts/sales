<?php

namespace Panthir\Domain\Common\Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'person_contact')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discriminator', type: 'string')]
abstract class AbstractContact
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue('NONE')]
    protected string $id;

    #[ORM\Column]
    protected string $name;

    #[ORM\Column]
    protected string $phone;

    #[ORM\Column]
    protected string $email;

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

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
