<?php

namespace Panthir\UI\Controller\User;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\User\Normalizer\DTO\RegisterDTO;
use Panthir\Application\UseCase\User\UserCreateHandler;
use Panthir\UI\Controller\APIController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/api/user')]
class Post extends APIController
{
    #[Route(path: '/', name: 'app_user_post', methods: 'POST')]
    public function post(
        UserCreateHandler $userCreateHandler,
        Request $request,
        HandlerRunner $runner,
    ): JsonResponse {
        $serializer = new Serializer(normalizers: [new ObjectNormalizer()]);

        /** TODO can this user insert this role level? */
        /** @var RegisterDTO $user */
        $user = $serializer->denormalize(
            data: $request->request->all(),
            type: RegisterDTO::class
        );

        $return = $runner($userCreateHandler, $user);

        return $this->response(items: $return);
    }
}
