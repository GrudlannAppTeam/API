<?php declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ApiExceptionInterface;
use App\Utils\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        if (!$event->getThrowable() instanceof ApiExceptionInterface) {
            return;
        }

        $event->setResponse(
            $this->createApiResponse($event->getThrowable())
        );
    }

    private function createApiResponse($exception)
    {
        return new ApiResponse($exception->getMessage(), null, [], $exception->getCode());
    }
}