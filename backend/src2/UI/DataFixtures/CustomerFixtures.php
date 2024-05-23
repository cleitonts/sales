<?php

namespace Panthir\UI\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\Customer\CustomerEditHandler;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerAddressDTO;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerContactDTO;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerCreateDTO;
use Panthir\Domain\Customer\ValueObject\AddressType;
use Panthir\Domain\Customer\ValueObject\ContactType;
use Panthir\Infrastructure\Faker\PortugalFactory;

class CustomerFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly HandlerRunner $handlerRunner,
        private readonly CustomerEditHandler $customerEditHandler
    ) {
    }

    public static function getGroups(): array
    {
        return ['customer'];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $rand = count(AddressType::cases()) - 1;

            $addresses = new ArrayCollection([]);
            while ($rand >= 0) {
                $fakerAddress = PortugalFactory::build();
                $addresses->add(
                    (new CustomerAddressDTO())
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
                    (new CustomerContactDTO())
                        ->setEmail($fakerContact->email())
                        ->setName($fakerContact->domainName())
                        ->setPhone($fakerContact->phoneNumber())
                        ->setType(ContactType::cases()[$rand]->value)
                );
                --$rand;
            }

            $faker = PortugalFactory::build();
            $this->handlerRunner->__invoke($this->customerEditHandler,
                (new CustomerCreateDTO())
                    ->setName($faker->firstName())
                    ->setSurname($faker->lastName())
                    ->setDocument($faker->taxpayerIdentificationNumber())
                    ->setAddresses($addresses)
                    ->setContacts($contacts)
            );
        }

        $manager->flush();
    }
}
