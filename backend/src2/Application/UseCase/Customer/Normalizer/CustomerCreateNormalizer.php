<?php

namespace Panthir\Application\UseCase\Customer\Normalizer;

use Panthir\Application\UseCase\Customer\CustomerEditHandler;
use Panthir\Domain\Customer\Model\Customer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomerCreateNormalizer implements NormalizerInterface
{
    /**
     * @param Customer $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return [
            'name' => $object->getName(),
            'surname' => $object->getSurname(),
            'id' => $object->getId(),
            'document' => $object->getDocument(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof Customer && CustomerEditHandler::class === $format;
    }
}
