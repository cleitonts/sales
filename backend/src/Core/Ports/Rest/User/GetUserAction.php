<?php

declare(strict_types=1);

namespace App\Core\Ports\Rest\User;

use App\Core\Application\Query\User\DTO\UserDTO;
use App\Core\Application\Query\User\GetUser\GetUserQuery;
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

final class GetUserAction
{
    use HandleTrait;

    private NormalizerInterface $normalizer;

    public function __construct(MessageBusInterface $queryBus, NormalizerInterface $normalizer)
    {
        $this->messageBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    #[Route(path: '/api/users/{id}', name: 'api_get_user', requirements: ['id' => '[\w-]+'], methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: HttpSpecEnum::STR_HTTP_OK->value,
        content: new OA\JsonContent(
            ref: new Model(type: UserDTO::class, groups: ['user_view']),
            type: 'object'
        )
    )]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: HttpSpecEnum::STR_HTTP_NOT_FOUND->value)]
    #[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: HttpSpecEnum::STR_HTTP_UNAUTHORIZED->value)]
    #[OA\Tag(name: 'User')]
    public function __invoke(Request $request): Response
    {
        $route = ParamFetcher::fromRequestAttributes($request);

        $user = $this->handle(new GetUserQuery($route->getRequiredInt('id')));

        return new JsonResponse(
            $this->normalizer->normalize($user, '', ['groups' => 'user_view']),
        );
    }
}
