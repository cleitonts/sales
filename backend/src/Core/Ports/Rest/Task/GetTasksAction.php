<?php

declare(strict_types=1);

namespace App\Core\Ports\Rest\Task;

use App\Core\Application\Query\Task\DTO\TaskDTO;
use App\Core\Application\Query\Task\GetTasks\GetTasksQuery;
use App\Shared\Infrastructure\Http\HttpSpecEnum;
use App\Shared\Infrastructure\Http\ParamFetcher;
use App\Shared\Infrastructure\ValueObject\PaginatedData;
use App\Shared\Infrastructure\ValueObject\Pagination;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class GetTasksAction
{
    use HandleTrait;

    private NormalizerInterface $normalizer;

    public function __construct(MessageBusInterface $queryBus, NormalizerInterface $normalizer)
    {
        $this->messageBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    #[Route('/api/tasks', methods: ['GET'])]
    #[OA\Parameter(
        name: 'execution_day',
        description: 'Execution day',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'search',
        description: 'Search phrase text',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Number of result items',
        in: 'query',
        schema: new OA\Schema(type: 'integer', default: Pagination::DEFAULT_LIMIT)
    )]
    #[OA\Parameter(
        name: 'offset',
        description: 'First result offset',
        in: 'query',
        schema: new OA\Schema(type: 'integer', default: Pagination::DEFAULT_OFFSET)
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: HttpSpecEnum::STR_HTTP_OK->value,
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model(type: TaskDTO::class, groups: ['task_view'])
            )
        )
    )]
    #[OA\Response(response: Response::HTTP_BAD_REQUEST, description: HttpSpecEnum::STR_HTTP_BAD_REQUEST->value)]
    #[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: HttpSpecEnum::STR_HTTP_UNAUTHORIZED->value)]
    #[OA\Tag(name: 'Task')]
    public function __invoke(Request $request): Response
    {
        $query = ParamFetcher::fromRequestQuery($request);

        $query = new GetTasksQuery(
            Pagination::fromRequest($request),
            $query->getNullableDate('execution_day'),
            $query->getNullableString('search')
        );

        /** @var PaginatedData $paginatedData */
        $paginatedData = $this->handle($query);

        return new JsonResponse(
            $this->normalizer->normalize($paginatedData->getData(), '', ['groups' => 'task_view']),
            Response::HTTP_OK,
            [HttpSpecEnum::HEADER_X_ITEMS_COUNT->value => $paginatedData->getCount()]
        );
    }
}
