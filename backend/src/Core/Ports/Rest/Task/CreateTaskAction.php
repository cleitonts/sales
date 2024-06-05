<?php

declare(strict_types=1);

namespace App\Core\Ports\Rest\Task;

use App\Core\Application\Command\Task\CreateTask\CreateTaskCommand;
use App\Shared\Infrastructure\Http\HttpSpecEnum;
use App\Shared\Infrastructure\Http\ParamFetcher;
use App\Shared\Infrastructure\Type\DateTimeFormatEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Date;

final class CreateTaskAction
{
    use HandleTrait;

    private RouterInterface $router;

    public function __construct(MessageBusInterface $commandBus, RouterInterface $router)
    {
        $this->messageBus = $commandBus;
        $this->router = $router;

    }

    #[Route('/api/tasks', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'JSON Payload',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'title', type: 'string'),
                new OA\Property(
                    property: 'execution_day',
                    type: 'string',
                    example: '2020-01-01 00:00:00',
                ),
                new OA\Property(property: 'description', type: 'string')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(response: Response::HTTP_CREATED, description: HttpSpecEnum::STR_HTTP_CREATED->value)]
    #[OA\Response(response: Response::HTTP_BAD_REQUEST, description: HttpSpecEnum::STR_HTTP_BAD_REQUEST->value)]
    #[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: HttpSpecEnum::STR_HTTP_UNAUTHORIZED->value)]
    #[OA\Tag(name: 'Task')]
    public function __invoke(Request $request): Response
    {
        $paramFetcher = ParamFetcher::fromRequestBody($request);

        $command = new CreateTaskCommand(
            $paramFetcher->getRequiredString('title'),
            $paramFetcher->getRequiredDate('execution_day'),
            $paramFetcher->getNullableString('description') ?? '',
        );

        $id = $this->handle($command);
        $resourceUrl = $this->router->generate('api_get_task', ['id' => $id]);

        return new JsonResponse(null, Response::HTTP_CREATED, [HttpSpecEnum::HEADER_LOCATION->value => $resourceUrl]);
    }
}
