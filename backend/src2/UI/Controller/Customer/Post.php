<?php

namespace Panthir\UI\Controller\Customer;

use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api/customer')]
class Post extends APIController
{
    /**
     * @throws ExceptionInterface
     * @throws HandlerException
     */
    #[Route(path: '/', name: 'app_customer_post', methods: 'POST')]
    public function post(
        CustomerEditHandler $customerEditHandler,
        Request $request,
        EntityManagerInterface $entityManager,
        HandlerRunner $handlerRunner,
    ): JsonResponse {
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'contacts' => [new SerializerHelper(CustomerContactDTO::class), 'collectionCallback'],
                //                'birthDate' => [new SerializerHelper(), 'dateCallback'],
                'addresses' => [new SerializerHelper(CustomerAddressDTO::class), 'collectionCallback'],
            ],
        ];

        $serializer = new Serializer(
            normalizers: [
                new ObjectNormalizer(
                    propertyTypeExtractor: new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]),
                    defaultContext: $defaultContext
                ),
            ]
        );

        /** @var CustomerCreateDTO $customer */
        $customer = $serializer->denormalize(
            data: $request->request->all(),
            type: CustomerCreateDTO::class,
        );

        $return = $handlerRunner($customerEditHandler, $customer);
        $entityManager->flush();

        return $this->response(items: $return);
    }
}
