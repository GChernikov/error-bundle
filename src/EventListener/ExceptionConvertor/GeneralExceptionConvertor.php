<?php

declare(strict_types=1);

namespace GChernikov\ErrorBundle\EventListener\ExceptionConvertor;

use GChernikov\OpenapiResponseAttributes\Dto\ErrorsResponse;
use GChernikov\OpenapiResponseAttributes\Dto\GeneralError;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class GeneralExceptionConvertor extends ExceptionConvertor
{
    public function buildResponse(Throwable $exception): Response
    {
        return $this->json(
            new ErrorsResponse([
                new GeneralError(
                    status: (string) Response::HTTP_INTERNAL_SERVER_ERROR,
                    detail: $exception->getMessage(),
                ),
            ]),
        );
    }

    public function supports(string $exceptionClass): bool
    {
        return is_subclass_of($exceptionClass, Throwable::class);
    }
}
