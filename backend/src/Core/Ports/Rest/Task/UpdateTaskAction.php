<?php

declare(strict_types=1);

namespace App\Core\Ports\Rest\Task;

use App\Core\Application\Command\Task\UpdateTask\UpdateTaskCommand;
use App\Shared\Infrastructure\Http\HttpSpecEnum;
use App\Shared\Infrastructure\Http\ParamFetcher;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class UpdateTaskAction
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    #[Route(path: '/api/tasks/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
    #[OA\RequestBody(
        description: 'JSON Payload',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'title', type: 'string'),
                new OA\Property(property: 'execution_day', type: 'string', example: '2020-01-01 00:00:00'),
                new OA\Property(property: 'description', type: 'string'),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: HttpSpecEnum::STR_HTTP_NO_CONTENT->value
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: HttpSpecEnum::STR_HTTP_NOT_FOUND->value
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: HttpSpecEnum::STR_HTTP_BAD_REQUEST->value
    )]
    #[OA\Response(
        response: Response::HTTP_UNAUTHORIZED,
        description: HttpSpecEnum::STR_HTTP_UNAUTHORIZED->value
    )]
    #[OA\Tag(name: 'Task')]
    public function __invoke(Request $request): Response
    {
        $route = ParamFetcher::fromRequestAttributes($request);
        $body = ParamFetcher::fromRequestBody($request);

        $command = new UpdateTaskCommand(
            $route->getRequiredInt('id'),
            $body->getRequiredString('title'),
            $body->getRequiredDate('execution_day'),
            $body->getNullableString('description') ?? '',
        );

        $this->handle($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
