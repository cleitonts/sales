<?php

namespace Panthir\Application\UseCase\Product\Normalizer;

use Panthir\Application\UseCase\Product\ProductCreateHandler;
use Panthir\Domain\Product\Model\Product;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductCreateNormalizer implements NormalizerInterface
{
    /**
     * @param Product $object
     *
     * @return array
     */
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        if (empty($object)) {
            return [];
        }

        return [
            'name' => $object->getName(),
            'id' => $object->getId(),
            'brand' => $object->getBrand(),
            'category' => $object->getCategory(),
            'value' => $object->getValue(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof Product && ProductCreateHandler::class === $format;
    }
}
