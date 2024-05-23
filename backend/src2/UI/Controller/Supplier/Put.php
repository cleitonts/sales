<?php

namespace Panthir\UI\Controller\Supplier;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\Services\SerializerHelper;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierAddressDTO;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierContactDTO;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierCreateDTO;
use Panthir\Application\UseCase\Supplier\SupplierEditHandler;
use Panthir\UI\Controller\APIController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api/supplier/{id}')]
class Put extends APIController
{
    #[Route(path: '/', name: 'app_supplier_put', methods: 'PUT')]
    public function put(
        SupplierEditHandler $supplierCreateHandler,
        Request $request,
        HandlerRunner $handlerRunner
    ): JsonResponse {
        $serializerHelperContacts = new SerializerHelper(SupplierContactDTO::class);
        $serializerHelperAddress = new SerializerHelper(SupplierAddressDTO::class);

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'contacts' => [$serializerHelperContacts, 'collectionCallback'],
                'addresses' => [$serializerHelperAddress, 'collectionCallback'],
            ],
        ];

        $serializer = new Serializer(
            normalizers: [new ObjectNormalizer(defaultContext: $defaultContext)]
        );

        /** @var SupplierCreateDTO $supplier */
        $supplier = $serializer->denormalize(
            data: $this->getData($request),
            type: SupplierCreateDTO::class
        );

        $return = $handlerRunner($supplierCreateHandler, $supplier);

        return $this->response(items: $return);
    }
}
