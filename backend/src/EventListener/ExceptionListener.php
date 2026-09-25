<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Exceptions\AttributeValidationException;
use App\Exceptions\MeSectionValidationException;
use App\Exceptions\PosititonDataValidationException;
use Doctrine\ORM\OptimisticLockException;

final class ExceptionListener
{
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        if ($exception instanceof HttpExceptionInterface) {
            if ($exception instanceof AttributeValidationException){
                $response = new JsonResponse([
                    'status' => "Attribute Value Error",
                    'errors' => $exception->getErrors()
                ]);
            } else if ($exception instanceof MeSectionValidationException){
                $response = new JsonResponse([
                    'status' => "Error in the Me section",
                    'errors' => $exception->getErrors()
                ]);
            }else if($exception instanceof PosititonDataValidationException){
                $response = new JsonResponse([
                    'status' => "Error in the position data",
                    'errors' => $exception->getErrors()
                ]);
            } 
            else{
                $response = new JsonResponse(['message'=>$exception->getMessage(), 'status'=>$exception->getStatusCode()]);
            }
            
        }else{
                $response = new JsonResponse(['message'=>$exception->getMessage(), 'status'=>500]);
            }
        if ($exception instanceof OptimisticLockException){
                $response = new JsonResponse(['message'=>"Oops, someone has already updated this data. Reload the page to get the latest information", 'status'=>409], 409);
        } 
        $event->setResponse($response);
    }
}
