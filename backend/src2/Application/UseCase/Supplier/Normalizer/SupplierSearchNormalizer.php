<?php

namespace Panthir\Application\UseCase\Supplier\Normalizer;

use Panthir\Application\UseCase\Supplier\SupplierSearchHandler;
use Panthir\Domain\Supplier\Model\Supplier;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SupplierSearchNormalizer implements NormalizerInterface
{
    /**
     * @param Supplier $object
     *
     * @return array
     */
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        if (empty($object)) {
            return [];
        }

        /**
         * @var $r Supplier
         */
        $arr = [];
        foreach ($object as $r) {
            $arr[] = [
                'name' => $r->getName(),
                'id' => $r->getId(),
                'nickName' => $r->getNickName(),
                'document' => $r->getDocument(),
                'secondaryDocument' => $r->getSecondaryDocument(),
            ];
        }

        return $arr;
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return is_array($data) && SupplierSearchHandler::class === $format;
    }
}
