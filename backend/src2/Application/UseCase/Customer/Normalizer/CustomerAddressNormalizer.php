<?php

namespace Panthir\Application\UseCase\Customer\Normalizer;

use Panthir\Application\UseCase\Customer\CustomerSearchHandler;
use Panthir\Domain\Customer\Model\CustomerAddress;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomerAddressNormalizer implements NormalizerInterface
{
    /**
     * @param CustomerAddress $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'city' => $object->getCity(),
            'addressComplement' => $object->getAddressComplement(),
            'address' => $object->getAddress(),
            'country' => $object->getCountry(),
            'district' => $object->getDistrict(),
            'number' => $object->getNumber(),
            'zip' => $object->getZip(),
            'type' => $object->getType(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof CustomerAddress && CustomerSearchHandler::class === $format;
    }
}
