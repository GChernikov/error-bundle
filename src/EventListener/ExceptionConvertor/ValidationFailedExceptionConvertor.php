<?php

declare(strict_types=1);

namespace GChernikov\ErrorBundle\EventListener\ExceptionConvertor;

use GChernikov\OpenapiResponseAttributes\Dto\ErrorsValidationResponse;
use GChernikov\OpenapiResponseAttributes\Dto\ValidationError;
use GChernikov\OpenapiResponseAttributes\Dto\ValidationErrorSource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;
use Webmozart\Assert\Assert;

class ValidationFailedExceptionConvertor extends ExceptionConvertor
{
    public function buildResponse(ValidationFailedException|Throwable $exception): Response
    {
        Assert::isInstanceOf($exception, ValidationFailedException::class);

        $mapper = static fn (ConstraintViolation $v): ValidationError => new ValidationError(
            source: new ValidationErrorSource(parameter: $v->getPropertyPath()),
            detail: (string) $v->getMessage(),
        );

        return $this->json(
            data: new ErrorsValidationResponse(
                array_map($mapper, [...$exception->getViolations()]),
            ),
            status: Response::HTTP_BAD_REQUEST,
        );
    }

    public function supports(string $exceptionClass): bool
    {
        return is_a($exceptionClass, ValidationFailedException::class, true);
    }
}
