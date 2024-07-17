<?php

declare(strict_types=1);

namespace App\Core\Ports\Rest\User;

use App\Core\Application\Command\User\CreateUser\CreateUserCommand;
use App\Shared\Infrastructure\Http\HttpSpecEnum;
use App\Shared\Infrastructure\Http\ParamFetcher;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

final class CreateUserAction
{
    use HandleTrait;

    private RouterInterface $router;

    public function __construct(MessageBusInterface $commandBus, RouterInterface $router)
    {
        $this->messageBus = $commandBus;
        $this->router = $router;
    }

    #[Route('/api/users', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'JSON Payload',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'username', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
                new OA\Property(property: 'password_repeat', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(response: Response::HTTP_CREATED, description: HttpSpecEnum::STR_HTTP_CREATED->value)]
    #[OA\Response(response: Response::HTTP_BAD_REQUEST, description: HttpSpecEnum::STR_HTTP_BAD_REQUEST->value)]
    #[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: HttpSpecEnum::STR_HTTP_UNAUTHORIZED->value)]
    #[OA\Tag(name: 'User')]
    public function __invoke(Request $request): Response
    {
        $paramFetcher = ParamFetcher::fromRequestBody($request);

        $command = new CreateUserCommand(
            $paramFetcher->getRequiredString('username'),
            $paramFetcher->getRequiredString('password'),
            $paramFetcher->getRequiredString('password_repeat'),
        );

        $id = $this->handle($command);
        $resourceUrl = $this->router->generate('api_get_user', ['id' => $id]);

        return new JsonResponse(null, Response::HTTP_CREATED, [HttpSpecEnum::HEADER_LOCATION->value => $resourceUrl]);
    }
}
