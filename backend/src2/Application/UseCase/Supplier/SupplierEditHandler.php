<?php

namespace Panthir\Application\UseCase\Supplier;

use Doctrine\ORM\EntityManagerInterface;
use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\Common\Handler\BeforeExecutedHandlerInterface;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierAddressDTO;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierContactDTO;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierCreateDTO;
use Panthir\Domain\Supplier\Model\Supplier;
use Panthir\Domain\Supplier\Model\SupplierAddress;
use Panthir\Domain\Supplier\Model\SupplierContact;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SupplierEditHandler extends AbstractHandler implements BeforeExecutedHandlerInterface
{
    private ?Supplier $supplier;

    public function __construct(
        EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct(entityManager: $entityManager);
    }

    public function supports($object): bool
    {
        return $object instanceof SupplierCreateDTO;
    }

    /**
     * @param SupplierCreateDTO $model
     *
     * @throws InvalidFieldException
     */
    public function beforeExecuted($model): void
    {
        $errors = $this->validator->validate($model);

        if (count($errors) > 0) {
            throw new InvalidFieldException((string) $errors, 400);
        }

        if (!empty($model->getId())) {
            $this->supplier = $this->entityManager->getRepository(Supplier::class)->find($model->getId());
        } else {
            $this->supplier = (new Supplier())->setUuid(Uuid::uuid4());
        }
    }

    /**
     * @param SupplierCreateDTO $model
     *
     * @throws InvalidFieldException
     */
    public function execute($model): Supplier
    {
        $this->supplier->setName($model->getName());
        $this->supplier->setDocument($model->getDocument());
        $this->supplier->setSecondaryDocument($model->getSecondaryDocument());
        $this->supplier->setNickName($model->getNickName());
        $this->supplier->setAdditionalInformation($model->getAdditionalInformation());
        $this->entityManager->persist($this->supplier);

        /** @var SupplierAddressDTO $address */
        foreach ($model->getAddresses()->getValues() as $address) {
            $this->handleAddress($address);
        }

        /** @var SupplierContactDTO $contact */
        foreach ($model->getContacts()->getValues() as $contact) {
            $this->handleContact($contact);
        }

        return $this->supplier;
    }

    /**
     * @throws InvalidFieldException
     */
    private function handleAddress(SupplierAddressDTO $model): void
    {
        $errors = $this->validator->validate($model);

        if (count($errors) > 0) {
            throw new InvalidFieldException((string) $errors, 400);
        }

        if (null !== $model->getId()) {
            $address = $this->entityManager->getRepository(SupplierAddress::class)->find($model->getId());
            if (empty($address)) {
                throw new InvalidFieldException('Invalid Address Id', 400);
            }

            if ($model->getDelete()) {
                $this->supplier->removeAddresses($address);
                $this->entityManager->remove($address);

                return;
            }
        } else {
            $address = (new SupplierAddress())->setUuid(Uuid::uuid4());
        }

        $address
            ->setPerson($this->supplier)
            ->setAddress($model->getAddress())
            ->setAddressComplement($model->getAddressComplement())
            ->setCity($model->getCity())
            ->setCountry($model->getCountry())
            ->setDistrict($model->getDistrict())
            ->setType($model->getType())
            ->setNumber($model->getNumber())
            ->setZip($model->getZip());

        $this->supplier->addAddresses($address);
        $this->entityManager->persist($address);
    }

    /**
     * @throws InvalidFieldException
     */
    private function handleContact(SupplierContactDTO $model): void
    {
        $errors = $this->validator->validate($model);

        if (count($errors) > 0) {
            throw new InvalidFieldException((string) $errors, 400);
        }

        if (null !== $model->getId()) {
            $contactEntity = $this->entityManager->getRepository(SupplierContact::class)->find($model->getId());
            if (empty($contactEntity)) {
                throw new InvalidFieldException('Invalid contact id', 400);
            }

            if ($model->getDelete()) {
                $this->supplier->removeContacts($contactEntity);
                $this->entityManager->remove($contactEntity);

                return;
            }
        } else {
            $contactEntity = (new SupplierContact())->setUuid(Uuid::uuid4());
        }

        $contactEntity
            ->setPerson($this->supplier)
            ->setName($model->getName())
            ->setEmail($model->getEmail())
            ->setPhone($model->getPhone())
            ->setType($model->getType());

        $this->supplier->addContacts($contactEntity);
        $this->entityManager->persist($contactEntity);
    }
}
