<?php

namespace Panthir\Application\UseCase\Customer;

use Doctrine\ORM\EntityManagerInterface;
use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\Common\Handler\BeforeExecutedHandlerInterface;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerAddressDTO;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerContactDTO;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerCreateDTO;
use Panthir\Domain\Customer\Model\Customer;
use Panthir\Domain\Customer\Model\CustomerAddress;
use Panthir\Domain\Customer\Model\CustomerContact;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerEditHandler extends AbstractHandler implements BeforeExecutedHandlerInterface
{
    private ?Customer $customer;

    public function __construct(
        EntityManagerInterface $entityManager,
        protected MessageBusInterface $bus,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct(entityManager: $entityManager);
    }

    public function supports($object): bool
    {
        return $object instanceof CustomerCreateDTO;
    }

    /**
     * @throws InvalidFieldException
     */
    public function beforeExecuted($model): void
    {
        $errors = $this->validator->validate($model);

        if (count($errors) > 0) {
            throw new InvalidFieldException((string) $errors, 400);
        }

        if (!empty($model->getId())) {
            $this->customer = $this->entityManager->getRepository(Customer::class)->find($model->getId());
        } else {
            $this->customer = (new Customer())->setUuid(Uuid::uuid4());
        }
    }

    /**
     * @param CustomerCreateDTO $model
     *
     * @throws InvalidFieldException
     */
    public function execute($model): Customer
    {
        $this->customer
            ->setName($model->getName())
            ->setDocument($model->getDocument())
            ->setSurname($model->getSurname())
            ->setBirthDate($model->getRawBirthDate())
            ->setSecondaryDocument($model->getSecondaryDocument())
            ->setAdditionalInformation($model->getAdditionalInformation());

        $this->entityManager->persist($this->customer);

        /** @var CustomerAddressDTO $address */
        foreach ($model->getAddresses() as $address) {
            $this->handleAddress($address);
        }

        /** @var CustomerContactDTO $contact */
        foreach ($model->getContacts() as $contact) {
            $this->handleContact($contact);
        }

        return $this->customer;
    }

    /**
     * @throws InvalidFieldException
     */
    private function handleAddress(CustomerAddressDTO $model): void
    {
        $errors = $this->validator->validate($model);

        if (count($errors) > 0) {
            throw new InvalidFieldException((string) $errors, 400);
        }

        if (null !== $model->getId()) {
            $address = $this->entityManager->getRepository(CustomerAddress::class)->find($model->getId());
            if (empty($address)) {
                throw new InvalidFieldException('Invalid Address Id', 400);
            }

            if ($model->getDelete()) {
                $this->customer->removeAddresses($address);
                $this->entityManager->remove($address);

                return;
            }
        } else {
            $address = (new CustomerAddress())->setUuid(Uuid::uuid4());
        }

        $address
            ->setPerson($this->customer)
            ->setAddress($model->getAddress())
            ->setAddressComplement($model->getAddressComplement())
            ->setCity($model->getCity())
            ->setCountry($model->getCountry())
            ->setDistrict($model->getDistrict())
            ->setType($model->getType())
            ->setNumber($model->getNumber())
            ->setZip($model->getZip());

        $this->customer->addAddresses($address);
        $this->entityManager->persist($address);
    }

    /**
     * @throws InvalidFieldException
     */
    private function handleContact(CustomerContactDTO $model): void
    {
        $errors = $this->validator->validate($model);

        if (count($errors) > 0) {
            throw new InvalidFieldException((string) $errors, 400);
        }

        if (null !== $model->getId()) {
            $contactEntity = $this->entityManager->getRepository(CustomerContact::class)->find($model->getId());
            if (empty($contactEntity)) {
                throw new InvalidFieldException('Invalid contact id', 400);
            }

            if ($model->getDelete()) {
                $this->customer->removeContacts($contactEntity);
                $this->entityManager->remove($contactEntity);

                return;
            }
        } else {
            $contactEntity = (new CustomerContact())->setUuid(Uuid::uuid4());
        }

        $contactEntity
            ->setPerson($this->customer)
            ->setName($model->getName())
            ->setEmail($model->getEmail())
            ->setPhone($model->getPhone())
            ->setType($model->getType());

        $this->customer->addContacts($contactEntity);
        $this->entityManager->persist($contactEntity);
    }
}
