<?php

namespace App\Gateway\Http;

use App\Gateway\DTOs\ForwardTargetDTO;
use Illuminate\Http\Client\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

final class ResponseNormalizer
{
    private const FORWARD_HEADERS = [
        'content-type',
        'content-length',
        'content-encoding',
        'cache-control',
        'x-correlation-id',
        'x-request-id',
    ];

    public static function toResponse(HttpResponse $res): Response
    {
        $response = new Response($res->body(), $res->status());

        foreach (self::FORWARD_HEADERS as $header) {
            $value = $res->header($header);
            if ($value) {
                $response->headers->set($header, $value);
            }
        }

        return $response;
    }

    public static function toErrorResponse(
        HttpResponse $res,
        ForwardTargetDTO $target,
        ?string $correlationId = null
    ): Response {
        $originalBody = $res->json() ?? ['detail' => $res->body()];

        $body = [
            'status' => 'failed',
            'service' => $target->service,
            'target_path' => $target->path,
            'http_status' => $res->status(),
            'correlation_id' => $correlationId,
            'error' => $originalBody,
        ];

        $response = new Response(
            json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            $res->status(),
            ['Content-Type' => 'application/json']
        );

        if ($cid = $res->header('x-correlation-id')) {
            $response->headers->set('x-correlation-id', $cid);
        }

        return $response;
    }
}
