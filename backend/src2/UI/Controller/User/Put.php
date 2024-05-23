<?php

namespace Panthir\UI\Controller\User;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\User\Normalizer\DTO\PasswordUpdateDTO;
use Panthir\Application\UseCase\User\UpdatePasswordHandler;
use Panthir\UI\Controller\APIController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api/user/')]
class Put extends APIController
{
    #[Route(path: 'updatePassword', name: 'app_user_update_password', methods: 'PUT')]
    public function updatePassword(
        UpdatePasswordHandler $passwordRecoveryHandler,
        Request $request,
        HandlerRunner $runner
    ): JsonResponse {
        $serializer = new Serializer(normalizers: [new ObjectNormalizer()]);

        /** @var PasswordUpdateDTO $user */
        $user = $serializer->denormalize(
            data: $request->request->all(),
            type: PasswordUpdateDTO::class
        );

        $return = $runner($passwordRecoveryHandler, $user);

        return $this->response(items: $return);
    }
}
