<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Http\Controllers;

use EdineiValdameri\Laravel\Audit\Models\Audit;
use EdineiValdameri\Laravel\Audit\Models\Builders\AuditBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditController
{
    public function index(Request $request): JsonResponse
    {
        $audits = Audit::query()
            ->when($request->has('schema'), function (AuditBuilder $query) use ($request) {
                $query->schema((string) $request->string('schema'));
            })
            ->when($request->has('table'), function (AuditBuilder $query) use ($request) {
                $query->table((string) $request->string('table'));
            })
            ->when($request->has('event'), function (AuditBuilder $query) use ($request) {
                $query->event((string) $request->string('event'));
            })
            ->paginate();

        return response()->json($audits);
    }
}
