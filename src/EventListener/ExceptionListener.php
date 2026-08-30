<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ExceptionListener
{
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof HttpExceptionInterface) {
            $response = new JsonResponse(['message'=>$exception->getMessage(), 'status'=>$exception->getStatusCode()]);
            $event->setResponse($response);
        }
    }
}
