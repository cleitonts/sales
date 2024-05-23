<?php

namespace Panthir\Infrastructure\EventSubscriber;

use Panthir\Application\Services\Notify\Notify;
use Panthir\Infrastructure\CommonBundle\Exception\CustomExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Notify $notify,
        private string $env,
        protected readonly LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException) {
            $message = $exception->getMessage();
            $code = 404;
        } elseif ($exception instanceof CustomExceptionInterface) {
            $message = $exception->getMessage();
            $code = $exception->getCode();
        } else {
            return;
        }

        $this->logger->error($message);
        $this->notify->addMessage($this->notify::ERROR, $message);
        $returnable = $this->notify->newReturn($message);

        $customResponse = JsonResponse::fromJsonString(
            $returnable, $code, ['Symfony-Debug-Toolbar-Replace' => 1]
        );

        $event->allowCustomResponseCode();
        $event->setResponse($customResponse);
    }
}
