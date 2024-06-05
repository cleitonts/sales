<?php

declare(strict_types=1);

namespace App\Core\Ports\Rest\AuthToken;

use App\Core\Application\Command\AuthToken\CreateAuthToken\CreateAuthTokenCommand;
use App\Shared\Infrastructure\Http\HttpSpecEnum;
use App\Shared\Infrastructure\Http\ParamFetcher;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class CreateAuthTokenAction
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    #[Route('/api/auth-token', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'Create auth token',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'username', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: HttpSpecEnum::STR_HTTP_CREATED->value,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'token', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: HttpSpecEnum::STR_HTTP_BAD_REQUEST->value
    )]
    #[OA\Response(
        response: Response::HTTP_UNAUTHORIZED,
        description: HttpSpecEnum::STR_HTTP_UNAUTHORIZED->value
    )]
    #[OA\Tag(name: 'Auth token')]
    public function __invoke(Request $request): Response
    {
        $paramFetcher = ParamFetcher::fromRequestBody($request);

        $token = $this->handle(new CreateAuthTokenCommand(
            $paramFetcher->getRequiredString('username'),
            $paramFetcher->getRequiredString('password')
        ));

        return new JsonResponse(['token' => $token], Response::HTTP_CREATED);
    }
}
