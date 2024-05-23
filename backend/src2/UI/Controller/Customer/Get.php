<?php

namespace Panthir\UI\Controller\Customer;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\Customer\CustomerSearchHandler;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerSearchDTO;
use Panthir\Domain\Customer\ValueObject\AddressType;
use Panthir\Domain\Customer\ValueObject\ContactType;
use Panthir\Infrastructure\CommonBundle\Exception\HandlerException;
use Panthir\Infrastructure\CommonBundle\Exception\InvalidFieldException;
use Panthir\UI\Controller\APIController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api/customer')]
class Get extends APIController
{
    /**
     * @throws ExceptionInterface
     * @throws HandlerException
     */
    #[Route(path: '/', name: 'app_customer_get_all', methods: 'GET')]
    public function getAll(
        HandlerRunner $runner,
        CustomerSearchHandler $customerSearchHandler,
        Request $request
    ): JsonResponse {
        $serializer = new Serializer(normalizers: [new ObjectNormalizer()]);

        /** @var CustomerSearchDTO $user */
        $searchDTO = $serializer->denormalize(
            data: $request->query->all(),
            type: CustomerSearchDTO::class
        );

        $users = $runner($customerSearchHandler, $searchDTO);

        return $this->response(items: $users);
    }

    /**
     * @throws InvalidFieldException
     * @throws HandlerException
     */
    #[Route(path: '/{id}', name: 'app_customer_get', methods: 'GET')]
    public function get(
        string $id,
        HandlerRunner $runner,
        CustomerSearchHandler $customerSearchHandler
    ): JsonResponse {
        $searchDTO = new CustomerSearchDTO(id: $id);

        $customer = $runner($customerSearchHandler, $searchDTO);
        if (null === $customer) {
            throw new InvalidFieldException('The given Id is invalid', 400);
        }

        return $this->response(items: $customer);
    }

    #[Route(path: '/address/types', name: 'app_customer_address_types_get', methods: 'GET')]
    public function getAddressTypes(): JsonResponse
    {
        return $this->response(items: AddressType::cases());
    }

    #[Route(path: '/contact/types', name: 'app_customer_contact_types_get', methods: 'GET')]
    public function getContactTypes(): JsonResponse
    {
        return $this->response(items: ContactType::cases());
    }
}
