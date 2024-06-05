<?php

declare(strict_types=1);

namespace App\Core\Ports\Rest\Task;

use App\Core\Application\Command\Task\MakeTaskDeclined\MakeTaskDeclinedCommand;
use App\Shared\Infrastructure\Http\HttpSpecEnum;
use App\Shared\Infrastructure\Http\ParamFetcher;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class MakeTaskDeclinedAction
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    #[Route(path: '/api/tasks/{id}/status/declined', methods: ['PATCH'])]
    #[OA\Patch(
        description: 'Make task declined'
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
        response: Response::HTTP_UNAUTHORIZED,
        description: HttpSpecEnum::STR_HTTP_UNAUTHORIZED->value
    )]
    #[OA\Tag(name: 'Task')]
    public function __invoke(Request $request): Response
    {
        $route = ParamFetcher::fromRequestAttributes($request);

        $this->handle(new MakeTaskDeclinedCommand($route->getRequiredInt('id')));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
