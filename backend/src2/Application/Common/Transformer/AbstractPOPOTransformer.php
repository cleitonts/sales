<?php

namespace Panthir\Application\Common\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Panthir\Application\Common\POPO\AbstractPOPO;

abstract class AbstractPOPOTransformer extends AbstractPOPO
{
    public static function transformFromObjects(iterable $objects): array
    {
        $dto = [];
        foreach ($objects as $object) {
            $dto[] = static::transformFromObject($object);
        }

        return $dto;
    }

    public static function transformFromObjectsToCollection(iterable $objects): ArrayCollection
    {
        $temp = new ArrayCollection();
        foreach ($objects as $object) {
            $temp->add(static::transformFromObject($object));
        }

        return $temp;
    }
}
