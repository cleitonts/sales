<?php

namespace Panthir\Application\UseCase\Supplier\Normalizer;

use Panthir\Application\UseCase\Supplier\SupplierSearchHandler;
use Panthir\Domain\Supplier\Model\SupplierContact;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SupplierContactNormalizer implements NormalizerInterface
{
    /**
     * @param SupplierContact $object
     *
     * @return array
     */
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'email' => $object->getEmail(),
            'phone' => $object->getPhone(),
            'type' => $object->getType(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof SupplierContact && SupplierSearchHandler::class === $format;
    }
}
