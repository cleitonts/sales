<?php

namespace Panthir\Application\UseCase\Customer\Normalizer;

use Panthir\Application\UseCase\Customer\CustomerSearchHandler;
use Panthir\Domain\Customer\Model\Customer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomerSearchNormalizer implements NormalizerInterface
{
    /**
     * @param Customer $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        if (empty($object)) {
            return [];
        }

        /**
         * @var $r Customer
         */
        $arr = [];
        foreach ($object as $r) {
            $arr[] = [
                'name' => $r->getName(),
                'id' => $r->getId(),
                'surname' => $r->getSurname(),
                'document' => $r->getDocument(),
                'secondaryDocument' => $r->getSecondaryDocument(),
                'birthDate' => $r->getBirthDate(),
            ];
        }

        return $arr;
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return is_array($data) && CustomerSearchHandler::class === $format;
    }
}
