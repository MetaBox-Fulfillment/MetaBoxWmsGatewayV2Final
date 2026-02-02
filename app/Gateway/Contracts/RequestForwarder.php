<?php

namespace App\Gateway\Contracts;

use App\Gateway\DTOs\ForwardTargetDTO;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequestForwarder
{
    public function forward(Request $request, ForwardTargetDTO $target): Response;
}
