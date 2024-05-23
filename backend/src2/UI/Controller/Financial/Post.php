<?php

namespace Panthir\UI\Controller\Financial;

use Doctrine\ORM\EntityManagerInterface;
use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\Services\SerializerHelper;
use Panthir\Application\UseCase\Customer\CustomerEditHandler;
use Panthir\Application\UseCase\Financial\Normalizer\DTO\InstallmentDTO;
use Panthir\Application\UseCase\Financial\Normalizer\DTO\TitleCreateDTO;
use Panthir\UI\Controller\APIController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api/financial')]
class Post extends APIController
{
    #[Route(path: '/', name: 'app_financial_post', methods: 'POST')]
    public function post(
        CustomerEditHandler $customerCreateHandler,
        Request $request,
        EntityManagerInterface $entityManager,
        HandlerRunner $handlerRunner
    ): JsonResponse {
        $callbacks = [];
        $callbacks = [
            AbstractNormalizer::CALLBACKS => [
                'installments' => [new SerializerHelper(InstallmentDTO::class, $callbacks), 'collectionCallback'],
                'date' => [new SerializerHelper(InstallmentDTO::class, $callbacks), 'dateCallback'],
            ],
        ];

        $serializer = new Serializer(
            normalizers: [new ObjectNormalizer(defaultContext: $callbacks)]
        );

        /** @var TitleCreateDTO $title */
        $title = $serializer->denormalize(
            data: $request->request->all(),
            type: TitleCreateDTO::class
        );

        $return = $handlerRunner($customerCreateHandler, $title);
        $entityManager->flush();

        return $this->response(items: $return);
    }
}
