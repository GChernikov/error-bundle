<?php

declare(strict_types=1);

namespace GChernikov\ErrorBundle\EventListener\ExceptionConvertor;

use GChernikov\OpenapiResponseAttributes\Dto\ErrorsResponse;
use GChernikov\OpenapiResponseAttributes\Dto\GeneralError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use Webmozart\Assert\Assert;

class HttpExceptionConvertor extends ExceptionConvertor
{
    public function buildResponse(HttpExceptionInterface|Throwable $exception): Response
    {
        Assert::isInstanceOf($exception, HttpExceptionInterface::class);

        $data = new ErrorsResponse([
            new GeneralError(
                status: (string) $exception->getStatusCode(),
                detail: $exception->getMessage(),
            ),
        ]);

        return $this->json(
            $data,
            status: $exception->getStatusCode(),
            headers: $exception->getHeaders(),
        );
    }

    public function supports(string $exceptionClass): bool
    {
        return is_subclass_of($exceptionClass, HttpExceptionInterface::class);
    }
}
