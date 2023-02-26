<?php

declare(strict_types=1);

namespace GChernikov\ErrorBundle\EventListener;

use GChernikov\ErrorBundle\EventListener\ExceptionConvertor\ExceptionConvertor;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionToResponseListener
{
    private readonly bool $debug;

    public function __construct(
        private readonly ExceptionConvertor $exceptionConvertor,
        KernelInterface $kernel
    ) {
        $this->debug = $kernel->isDebug();
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($this->debug) {
            return;
        }

        $event->setResponse(
            $this->exceptionConvertor->buildResponse(
                $event->getThrowable()
            )
        );
    }
}
