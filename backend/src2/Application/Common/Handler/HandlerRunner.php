<?php

namespace Panthir\Application\Common\Handler;

use Panthir\Infrastructure\CommonBundle\Exception\HandlerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class HandlerRunner
{
    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    /** TODO unit testing here.
     * @throws HandlerException
     */
    public function __invoke(CommonHandlerInterface $handler, $model, bool $normalize = true): mixed
    {
        if (!$handler->supports($model)) {
            throw new HandlerException($model::class.' Not supported by: '.$handler::class, 500);
        }

        if ($handler instanceof BeforeExecutedHandlerInterface) {
            $handler->beforeExecuted($model);
        }

        $returnedObject = $handler->execute($model);

        if ($handler instanceof AfterExecutedHandlerInterface) {
            $handler->afterExecuted($model);
        }

        $handler->flush();

        if ($normalize) {
            return $this->serializer->normalize(
                $returnedObject,
                $handler::class,
                [
                    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                        return $object->getId();
                    },
                ]
            );
        }

        return $returnedObject;
    }
}
