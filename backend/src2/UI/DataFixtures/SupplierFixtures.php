<?php

namespace Panthir\UI\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierAddressDTO;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierContactDTO;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierCreateDTO;
use Panthir\Application\UseCase\Supplier\SupplierEditHandler;
use Panthir\Domain\Supplier\ValueObject\AddressType;
use Panthir\Domain\Supplier\ValueObject\ContactType;
use Panthir\Infrastructure\Faker\PortugalFactory;

class SupplierFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly HandlerRunner $handlerRunner,
        private readonly SupplierEditHandler $supplierEditHandler
    ) {
    }

    public static function getGroups(): array
    {
        return ['supplier'];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $rand = count(AddressType::cases()) - 1;

            $addresses = new ArrayCollection([]);
            while ($rand >= 0) {
                $fakerAddress = PortugalFactory::build();
                $addresses->add(
                    (new SupplierAddressDTO())
                        ->setCountry($fakerAddress->country())
                        ->setDistrict((method_exists($fakerAddress, 'state')) ? $fakerAddress->state() : $fakerAddress->city())
                        ->setCity($fakerAddress->city())
                        ->setAddress($fakerAddress->streetAddress())
                        ->setNumber($fakerAddress->randomNumber(4))
                        ->setZip($fakerAddress->postcode())
                        ->setType(AddressType::cases()[$rand]->value)
                        ->setAddressComplement($fakerAddress->domainName())
                );
                --$rand;
            }

            $rand = count(ContactType::cases()) - 1;
            $contacts = new ArrayCollection([]);
            while ($rand >= 0) {
                $fakerContact = PortugalFactory::build();
                $contacts->add(
                    (new SupplierContactDTO())
                        ->setEmail($fakerContact->email())
                        ->setName($fakerContact->domainName())
                        ->setPhone($fakerContact->phoneNumber())
                        ->setType(ContactType::cases()[$rand]->value)
                );
                --$rand;
            }

            $faker = PortugalFactory::build();
            $this->handlerRunner->__invoke($this->supplierEditHandler,
                (new SupplierCreateDTO())
                    ->setName($faker->firstName())
                    ->setNickName($faker->lastName())
                    ->setDocument($faker->taxpayerIdentificationNumber())
                    ->setAddresses($addresses)
                    ->setContacts($contacts)
            );
        }

        $manager->flush();
    }
}
