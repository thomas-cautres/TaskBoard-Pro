<?php

declare(strict_types=1);

namespace App\Event\Api;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $validationFailedException = $exception->getPrevious();

        if (!$validationFailedException instanceof ValidationFailedException) {
            return;
        }

        $violations = $validationFailedException->getViolations();

        $event->setResponse(new JsonResponse(
            [
                'code' => 'INVALID_PARAMETERS',
                'message' => 'Invalid query parameters',
                'details' => array_reduce(
                    iterator_to_array($violations),
                    fn (array $acc, ConstraintViolationInterface $violation) => $acc + [$violation->getPropertyPath() => $violation->getMessage()],
                    []
                ),
            ], 400));
    }
}
