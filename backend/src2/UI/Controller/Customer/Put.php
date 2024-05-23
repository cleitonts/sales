<?php

namespace Panthir\UI\Controller\Customer;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\Services\SerializerHelper;
use Panthir\Application\UseCase\Customer\CustomerEditHandler;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerAddressDTO;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerContactDTO;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerCreateDTO;
use Panthir\Infrastructure\CommonBundle\Exception\HandlerException;
use Panthir\UI\Controller\APIController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api/customer/{id}')]
class Put extends APIController
{
    /**
     * @throws ExceptionInterface
     * @throws HandlerException
     */
    #[Route(path: '/', name: 'app_customer_put', methods: 'PUT')]
    public function put(
        CustomerEditHandler $customerCreateHandler,
        Request $request,
        HandlerRunner $handlerRunner
    ): JsonResponse {
        $serializerHelperContacts = new SerializerHelper(CustomerContactDTO::class);
        $serializerHelperAddress = new SerializerHelper(CustomerAddressDTO::class);

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'contacts' => [$serializerHelperContacts, 'collectionCallback'],
                'birthDate' => [new SerializerHelper(), 'dateCallback'],
                'addresses' => [$serializerHelperAddress, 'collectionCallback'],
            ],
        ];

        $serializer = new Serializer(
            normalizers: [new ObjectNormalizer(defaultContext: $defaultContext)]
        );

        /** @var CustomerCreateDTO $customer */
        $customer = $serializer->denormalize(
            data: $this->getData($request),
            type: CustomerCreateDTO::class
        );

        $return = $handlerRunner($customerCreateHandler, $customer);

        return $this->response(items: $return);
    }
}
