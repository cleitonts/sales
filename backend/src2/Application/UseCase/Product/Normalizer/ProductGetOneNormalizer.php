<?php

namespace Panthir\Application\UseCase\Product\Normalizer;

use Panthir\Application\UseCase\Product\ProductSearchHandler;
use Panthir\Domain\Product\Model\Product;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductGetOneNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param Product $object
     *
     * @return array
     *
     * @throws ExceptionInterface
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
            'category' => $this->normalizer->normalize($object->getCategory(), $format, $context),
            'value' => $object->getValue(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof Product && ProductSearchHandler::class === $format;
    }
}
