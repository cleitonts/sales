<?php

namespace Panthir\Application\UseCase\Product\Normalizer;

use Panthir\Application\UseCase\Product\ProductSearchHandler;
use Panthir\Domain\Product\Model\Product;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductSearchNormalizer implements NormalizerInterface
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

        /**
         * @var $r Product
         */
        $arr = [];
        foreach ($object as $r) {
            $arr[] = [
                'name' => $r->getName(),
                'id' => $r->getId(),
                'brand' => $r->getBrand()->getName(),
                'brandId' => $r->getBrand()->getId(),
                'category' => $r->getCategory()->getName(),
                'categoryId' => $r->getCategory()->getId(),
                'value' => $r->getValue(),
            ];
        }

        $arr[0]['totalItems'] = $r->getTotalItems();

        return $arr;
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return is_array($data) && ProductSearchHandler::class === $format;
    }
}
