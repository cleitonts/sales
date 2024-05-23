<?php

namespace Panthir\Application\UseCase\User\Normalizer;

use Panthir\Application\UseCase\User\UserSearchHandler;
use Panthir\Domain\User\Model\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserSearchIdNormalizer implements NormalizerInterface
{
    /**
     * @param User $object
     *
     * @return array
     */
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        return [
            'email' => $object->getEmail(),
            'id' => $object->getId(),
            'profile' => $object->getProfile(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof User && UserSearchHandler::class === $format;
    }
}
