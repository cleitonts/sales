<?php

namespace Panthir\Application\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerHelper
{
    public function __construct(
        private readonly ?string $modelName = null,
        private readonly ?array $callbacks = null
    ) {
    }

    public function collectionCallback($innerObject)
    {
        $serializer = new Serializer(normalizers: [new ObjectNormalizer(defaultContext: $this->callbacks)]);
        $collection = new ArrayCollection();

        foreach ($innerObject as $row) {
            $collection->add($serializer->denormalize(data: $row, type: $this->modelName));
        }

        return $collection;
    }

    public function dateCallback($value)
    {
        if ($value instanceof \DateTime) {
            return $value;
        }

        if (!empty($value)) {
            return date_create_from_format('d/m/Y', $value);
        }

        return null;
    }
}
