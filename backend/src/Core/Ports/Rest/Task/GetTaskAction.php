<?php

declare(strict_types=1);

namespace App\Core\Ports\Rest\Task;

use App\Core\Application\Query\Task\DTO\TaskDTO;
use App\Core\Application\Query\Task\GetTask\GetTaskQuery;
use App\Shared\Infrastructure\Http\HttpSpecEnum;
use App\Shared\Infrastructure\Http\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class GetTaskAction
{
    use HandleTrait;

    private NormalizerInterface $normalizer;

    public function __construct(MessageBusInterface $queryBus, NormalizerInterface $normalizer)
    {
        $this->messageBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    #[Route(path: '/api/tasks/{id}', name: 'api_get_task', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: HttpSpecEnum::STR_HTTP_OK->value,
        content: new OA\JsonContent(
            ref: new Model(type: TaskDTO::class, groups: ['task_view']),
            type: 'object'
        )
    )]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: HttpSpecEnum::STR_HTTP_NOT_FOUND->value)]
    #[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: HttpSpecEnum::STR_HTTP_UNAUTHORIZED->value)]
    #[OA\Tag(name: 'Task')]
    public function __invoke(Request $request): Response
    {
        $route = ParamFetcher::fromRequestAttributes($request);

        $task = $this->handle(new GetTaskQuery($route->getRequiredInt('id')));

        return new JsonResponse(
            $this->normalizer->normalize($task, '', ['groups' => 'task_view']),
        );
    }
}
