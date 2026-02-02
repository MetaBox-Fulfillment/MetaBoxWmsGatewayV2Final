<?php

namespace App\Http\Controllers\Gateway;

use App\Gateway\Contracts\RequestForwarder;
use App\Gateway\Contracts\RouteResolver;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class ProxyController
{
    public function __construct(
        private RouteResolver $resolver,
        private RequestForwarder $forwarder,
    ) {}

    public function handle(Request $request): Response
    {
        $target = $this->resolver->resolve($request);

        if (!$target) {
            return response()->json([
                'message' => 'No route matched for gateway forwarding.',
                'path' => $request->path(),
            ], 404);
        }

        return $this->forwarder->forward($request, $target);
    }
}
