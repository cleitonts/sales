<?php

namespace Panthir\Application\UseCase\User\Normalizer;

use Panthir\Application\UseCase\User\CreatePasswordRecoveryTokenHandler;
use Panthir\Application\UseCase\User\UpdatePasswordHandler;
use Panthir\Application\UseCase\User\UserCreateHandler;
use Panthir\Domain\User\Model\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserCreateNormalizer implements NormalizerInterface
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
            'profile' => $object->getProfile(),
            'id' => $object->getId(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof User
            && in_array($format, [
                UserCreateHandler::class,
                CreatePasswordRecoveryTokenHandler::class,
                UpdatePasswordHandler::class,
            ]);
    }
}
