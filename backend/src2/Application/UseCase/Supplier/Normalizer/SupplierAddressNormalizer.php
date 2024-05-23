<?php

namespace Panthir\Application\UseCase\Supplier\Normalizer;

use Panthir\Application\UseCase\Supplier\SupplierSearchHandler;
use Panthir\Domain\Supplier\Model\SupplierAddress;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SupplierAddressNormalizer implements NormalizerInterface
{
    /**
     * @param SupplierAddress $object
     *
     * @return array
     */
    public function normalize(mixed $object, ?string $format = null, array $context = [])
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

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof SupplierAddress && SupplierSearchHandler::class === $format;
    }
}
