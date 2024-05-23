<?php

namespace Panthir\Application\UseCase\User\Normalizer;

use Panthir\Application\UseCase\User\UserSearchHandler;
use Panthir\Domain\User\Model\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserSearchNormalizer implements NormalizerInterface
{
    /**
     * @param User $object
     *
     * @return array
     */
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        if (empty($object)) {
            return [];
        }

        /**
         * @var $r User
         */
        $arr = [];
        foreach ($object as $r) {
            $arr[] = [
                'email' => $r->getEmail(),
                'id' => $r->getId(),
                'profile' => $r->getProfile(),
            ];
        }

        return $arr;
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return is_array($data) && UserSearchHandler::class === $format;
    }
}
