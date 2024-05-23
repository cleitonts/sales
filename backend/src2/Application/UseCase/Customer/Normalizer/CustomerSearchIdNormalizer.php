<?php

namespace Panthir\Application\UseCase\Customer\Normalizer;

use Panthir\Application\UseCase\Customer\CustomerSearchHandler;
use Panthir\Domain\Customer\Model\Customer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CustomerSearchIdNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param Customer $object
     *
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
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
            'surname' => $object->getSurname(),
            'document' => $object->getDocument(),
            'secondaryDocument' => $object->getSecondaryDocument(),
            'birthDate' => $object->getBirthDate(),
            'additionalInformation' => $object->getAdditionalInformation(),
            'addresses' => $addresses,
            'contacts' => $contacts,
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof Customer && CustomerSearchHandler::class === $format;
    }
}
