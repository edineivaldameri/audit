<?php

declare(strict_types=1);

namespace Workbench\EdineiValdameri\Laravel\DynamicValidation\App\Http\Controllers;

use EdineiValdameri\Laravel\DynamicValidation\Http\Requests\DynamicFormRequest;
use Illuminate\Http\JsonResponse;

class TestController
{
    public function store(DynamicFormRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Validated successfully',
        ], 200);
    }
}
