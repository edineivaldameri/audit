<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

test('the audit_enabled() function must exist in the PostgreSQL database', function () {
    $count = DB::table(DB::raw('pg_proc'))
        ->where('proname', 'audit_enabled')
        ->count();

    expect($count)->toBeGreaterThan(0);
});

test('the audit_context() function must exist in the PostgreSQL database', function () {
    $count = DB::table(DB::raw('pg_proc'))
        ->where('proname', 'audit_context')
        ->count();

    expect($count)->toBeGreaterThan(0);
});

test('the audit() function must exist in the PostgreSQL database', function () {
    $count = DB::table(DB::raw('pg_proc'))
        ->where('proname', 'audit')
        ->count();

    expect($count)->toBeGreaterThan(0);
});

test('creates a trigger in the database', function () {
    Artisan::call('audit:install');

    $exists = DB::table(DB::raw('pg_trigger'))
        ->where('tgname', 'public_users_audit')
        ->exists();

    expect($exists)->toBeTrue();
});

test('test uninstall audit', function () {
    Artisan::call('audit:install');

    $exists = DB::table(DB::raw('pg_trigger'))
        ->where('tgname', 'public_users_audit')
        ->exists();

    expect($exists)->toBeTrue();

    Artisan::call('audit:uninstall');

    $exists = DB::table(DB::raw('pg_trigger'))
        ->where('tgname', 'public_users_audit')
        ->exists();

    expect($exists)->toBeFalse();
});
