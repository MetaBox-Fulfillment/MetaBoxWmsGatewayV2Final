<?php

namespace App\Gateway\Contracts;

use App\Gateway\DTOs\ForwardTargetDTO;
use Illuminate\Http\Request;

interface RouteResolver
{
    public function resolve(Request $request): ?ForwardTargetDTO;
}
