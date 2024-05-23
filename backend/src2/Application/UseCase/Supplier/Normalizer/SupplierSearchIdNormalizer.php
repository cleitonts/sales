<?php

namespace Panthir\Application\UseCase\Supplier\Normalizer;

use Panthir\Application\UseCase\Supplier\SupplierSearchHandler;
use Panthir\Domain\Supplier\Model\Supplier;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SupplierSearchIdNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param Supplier $object
     *
     * @return array
     *
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        $addresses = [];
        foreach ($object->getAddresses() as $address) {
            $addresses[] = $this->normalizer->normalize($address, $format, $context);
        }

        $contacts = [];
        foreach ($object->getContacts() as $contact) {
            $contacts[] = $this->normalizer->normalize($contact, $format, $context);
        }

        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'nickName' => $object->getNickName(),
            'document' => $object->getDocument(),
            'secondaryDocument' => $object->getSecondaryDocument(),
            'additionalInformation' => $object->getAdditionalInformation(),
            'addresses' => $addresses,
            'contacts' => $contacts,
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof Supplier && SupplierSearchHandler::class === $format;
    }
}
