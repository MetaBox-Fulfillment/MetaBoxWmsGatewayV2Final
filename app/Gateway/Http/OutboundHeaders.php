<?php

namespace App\Gateway\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class OutboundHeaders
{
    public static function build(Request $request): array
    {
        $allowed = array_map('strtolower', config('microservices.forward_headers', []));
        $headers = [];

        foreach ($request->headers->all() as $name => $values) {
            if (in_array(strtolower($name), $allowed, true)) {
                $headers[$name] = is_array($values) ? implode(',', $values) : (string) $values;
            }
        }

        foreach (config('microservices.outbound_headers', []) as $k => $v) {
            $headers[$k] = $v;
        }

        if (empty($headers['x-correlation-id'])) {
            $headers['x-correlation-id'] = (string) Str::uuid();
        }

        if (empty($headers['x-request-id'])) {
            $headers['x-request-id'] = (string) Str::uuid();
        }

        return $headers;
    }
}
