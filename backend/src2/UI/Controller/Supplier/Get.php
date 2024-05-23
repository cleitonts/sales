<?php

namespace Panthir\UI\Controller\Supplier;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierSearchDTO;
use Panthir\Application\UseCase\Supplier\SupplierSearchHandler;
use Panthir\Domain\Supplier\ValueObject\AddressType;
use Panthir\Domain\Supplier\ValueObject\ContactType;
use Panthir\Infrastructure\CommonBundle\Exception\HandlerException;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;
use Panthir\UI\Controller\APIController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api/supplier')]
class Get extends APIController
{
    /**
     * @throws ExceptionInterface
     * @throws HandlerException
     */
    #[Route(path: '/', name: 'app_supplier_get_all', methods: 'GET')]
    public function getAll(
        HandlerRunner $runner,
        SupplierSearchHandler $supplierSearchHandler,
        Request $request
    ): JsonResponse {
        $serializer = new Serializer(normalizers: [new ObjectNormalizer()]);

        /** @var SupplierSearchDTO $user */
        $searchDTO = $serializer->denormalize(
            data: $request->query->all(),
            type: SupplierSearchDTO::class
        );

        $users = $runner($supplierSearchHandler, $searchDTO);

        return $this->response(items: $users);
    }

    /**
     * @throws InvalidFieldException
     * @throws HandlerException
     */
    #[Route(path: '/{id}', name: 'app_supplier_get', methods: 'GET')]
    public function get(
        string $id,
        HandlerRunner $runner,
        SupplierSearchHandler $supplierSearchHandler
    ): JsonResponse {
        $searchDTO = new SupplierSearchDTO(id: $id);

        $supplier = $runner($supplierSearchHandler, $searchDTO);
        if (null === $supplier) {
            throw new InvalidFieldException('The given Id is invalid', 400);
        }

        return $this->response(items: $supplier);
    }

    #[Route(path: '/address/types', name: 'app_supplier_address_types_get', methods: 'GET')]
    public function getAddressTypes(): JsonResponse
    {
        return $this->response(items: AddressType::cases());
    }

    #[Route(path: '/contact/types', name: 'app_supplier_contact_types_get', methods: 'GET')]
    public function getContactTypes(): JsonResponse
    {
        return $this->response(items: ContactType::cases());
    }
}
