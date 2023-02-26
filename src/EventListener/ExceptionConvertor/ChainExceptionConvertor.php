<?php

declare(strict_types=1);

namespace GChernikov\ErrorBundle\EventListener\ExceptionConvertor;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class ChainExceptionConvertor extends ExceptionConvertor
{
    /**
     * @param array<ExceptionConvertor> $exceptionConvertors
     */
    public function __construct(
        private readonly iterable $exceptionConvertors,
        private readonly SerializerInterface $serializer
    ) {
        Assert::allIsInstanceOf($exceptionConvertors, ExceptionConvertor::class);

        parent::__construct($serializer);
    }

    public function supports(string $exceptionClass): bool
    {
        foreach ($this->exceptionConvertors as $exceptionConvertor) {
            if ($exceptionConvertor->supports($exceptionClass)) {
                return true;
            }
        }

        return false;
    }

    public function buildResponse(Throwable $exception): Response
    {
        foreach ($this->exceptionConvertors as $exceptionConvertor) {
            if ($exceptionConvertor->supports(get_class($exception))) {
                return $exceptionConvertor->buildResponse($exception);
            }
        }

        throw new InvalidArgumentException('Invalid configuration');
    }
}
