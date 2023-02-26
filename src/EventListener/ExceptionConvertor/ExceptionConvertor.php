<?php

declare(strict_types=1);

namespace GChernikov\ErrorBundle\EventListener\ExceptionConvertor;

use GChernikov\OpenapiResponseAttributes\Dto\ErrorsResponse;
use GChernikov\OpenapiResponseAttributes\Dto\ErrorsValidationResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

abstract class ExceptionConvertor
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    abstract public function supports(string $exceptionClass): bool;

    abstract public function buildResponse(Throwable $exception): Response;

    /**
     * @param array<string, string> $headers
     */
    protected function json(
        ErrorsResponse|ErrorsValidationResponse|array $data,
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $headers = [],
    ): JsonResponse {
        $jsonData = $this->serializer->serialize(
            $data,
            format: JsonEncoder::FORMAT,
        );

        return new JsonResponse(
            data: $jsonData,
            status: $status,
            headers: [...$headers, ['Content-Type' => 'application/vnd.api+json']],
            json: true,
        );
    }

    protected function text(
        string $message,
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
    ): Response {
        $response = new Response(
            content: $message,
            status: $status,
        );

        $response->headers->replace(
            headers: ['Content-Type' => 'text/plain'],
        );

        return $response;
    }
}
