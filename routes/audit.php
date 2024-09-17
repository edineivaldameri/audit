<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\Audit\Http\Controllers\AuditController;
use Illuminate\Support\Facades\Route;

Route::apiResource('audits', AuditController::class)->only(['index']);
