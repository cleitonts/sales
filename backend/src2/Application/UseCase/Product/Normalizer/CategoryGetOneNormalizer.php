<?php

namespace Panthir\Application\UseCase\Product\Normalizer;

use Panthir\Application\UseCase\Product\ProductSearchHandler;
use Panthir\Domain\Product\Model\Category;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategoryGetOneNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param Category $object
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
            'parent' => $this->normalizer->normalize($object->getParent(), $format, $context),
            'isLastLevel' => $object->isLastLevel(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof Category && ProductSearchHandler::class === $format;
    }
}
