<?php

namespace App\Gateway\Http;

use App\Gateway\Contracts\RequestForwarder;
use App\Gateway\Contracts\ServiceRegistry;
use App\Gateway\DTOs\ForwardTargetDTO;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MtbxHttpRequestForwarder implements RequestForwarder
{
    public function __construct(
        private ServiceRegistry $registry
    ) {}

    public function forward(Request $request, ForwardTargetDTO $target): Response
    {
        $url = $target->base_url . $target->path;

        $headers = OutboundHeaders::build($request);

        $apiKey = $this->registry->getApiKey($target->service);
        if ($apiKey) {
            $headers[$this->registry->getApiKeyHeader($target->service)] = $apiKey;
        }

        $correlationId = $headers['x-correlation-id'] ?? null;

        try {
            $res = $this->sendRequest($request, $url, $headers, $target->timeout_seconds);

            if ($res->failed()) {
                Log::warning('Gateway: Microservice returned error', [
                    'service' => $target->service,
                    'url' => $url,
                    'method' => $request->method(),
                    'status' => $res->status(),
                    'correlation_id' => $correlationId,
                ]);

                return ResponseNormalizer::toErrorResponse($res, $target, $correlationId);
            }

            return ResponseNormalizer::toResponse($res);

        } catch (ConnectionException $e) {
            Log::error('Gateway: Connection failed to microservice', [
                'service' => $target->service,
                'url' => $url,
                'method' => $request->method(),
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ]);

            return new Response(
                json_encode([
                    'message' => 'Service unavailable',
                    'correlation_id' => $correlationId,
                ]),
                503,
                ['Content-Type' => 'application/json']
            );

        } catch (\Throwable $e) {
            Log::error('Gateway: Unexpected error during forwarding', [
                'service' => $target->service,
                'url' => $url,
                'method' => $request->method(),
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ]);

            return new Response(
                json_encode([
                    'message' => 'Gateway error',
                    'correlation_id' => $correlationId,
                ]),
                502,
                ['Content-Type' => 'application/json']
            );
        }
    }

    private function sendRequest(Request $request, string $url, array $headers, int $timeout): \Illuminate\Http\Client\Response
    {
        $httpClient = Http::timeout($timeout);

        if ($this->isMultipartRequest($request)) {
            unset($headers['content-type']);

            $httpClient = $httpClient->withHeaders($headers);

            $multipart = [];

            $formFields = $request->except(array_keys($request->allFiles()));
            foreach ($formFields as $name => $value) {
                $multipart[] = [
                    'name' => $name,
                    'contents' => is_array($value) ? json_encode($value) : (string) $value,
                ];
            }

            foreach ($request->allFiles() as $key => $files) {
                $fileList = is_array($files) ? $files : [$files];
                foreach ($fileList as $index => $file) {
                    /** @var \Illuminate\Http\UploadedFile $file */
                    $fieldName = is_array($files) ? "{$key}[{$index}]" : $key;
                    $multipart[] = [
                        'name' => $fieldName,
                        'contents' => fopen($file->getRealPath(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ];
                }
            }

            return $httpClient->send(
                $request->method(),
                $url,
                [
                    'query' => $request->query(),
                    'multipart' => $multipart,
                ]
            );
        }

        return $httpClient
            ->withHeaders($headers)
            ->send(
                $request->method(),
                $url,
                [
                    'query' => $request->query(),
                    'body' => $request->getContent(),
                ]
            );
    }

    private function isMultipartRequest(Request $request): bool
    {
        $contentType = $request->header('content-type', '');
        return str_contains($contentType, 'multipart/form-data') || $request->allFiles();
    }
}
