<?php

namespace App\ServiceB\EventListener;

use App\ServiceB\Exception\InvalidPayloadException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class InvalidPayloadListener implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $ex = $event->getThrowable();
        if (!$ex instanceof InvalidPayloadException) {
            return;
        }
        /** @var InvalidPayloadException $ex */ 

        $responseCode = $ex->getCode();
        $event->setResponse(new JsonResponse(
            [
                'type' => '/doc/errors/invalid_payload',
                'title' => 'Invalid Payload',
                'detail' => $ex->getMessage(),
                'status' => $responseCode,
                'all_errors' => $ex->getErrorMessages()
            ], 
            $responseCode,
            ['Content-Type' => 'application/problem+json']
        ));
    }
    
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException'
        ];
    }
}