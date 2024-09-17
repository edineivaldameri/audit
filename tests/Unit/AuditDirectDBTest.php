<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\Audit\Models\Audit;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    Artisan::call('audit:install');
});

test('audit test in direct insertion into the database', function () {
    DB::unprepared(
        query: 'INSERT INTO public.users(name, email) VALUES (\'USER NAME\', \'email@email.test\');'
    );

    $audit = Audit::query()->first();

    expect(Audit::query()->count())->toEqual(1)
        ->and($audit)->not()->toBeNull()
        ->and($audit)->toBeInstanceOf(Audit::class)
        ->and($audit->event)->toEqual('INSERT')
        ->and($audit->schema)->toEqual('public')
        ->and($audit->table)->toEqual('users')
        ->and($audit->before)->toBeNull()
        ->and($audit->after['name'])->toEqual('USER NAME')
        ->and($audit->after['email'])->toEqual('email@email.test');
});

test('audit test in direct insertion and updating into the database', function () {
    DB::unprepared(
        query: 'INSERT INTO public.users(name, email) VALUES (\'USER NAME\', \'email@email.test\');'
    );

    DB::unprepared(
        query: 'UPDATE public.users SET name = \'USER UPDATE\' WHERE email = \'email@email.test\';'
    );

    $last = Audit::query()->orderBy('id')->first();
    $first = Audit::query()->orderByDesc('id')->first();

    expect(Audit::query()->count())->toEqual(2)
        ->and($last)->not()->toBeNull()
        ->and($last)->toBeInstanceOf(Audit::class)
        ->and($last->event)->toEqual('INSERT')
        ->and($last->schema)->toEqual('public')
        ->and($last->table)->toEqual('users')
        ->and($last->before)->toBeNull()
        ->and($last->after['name'])->toEqual('USER NAME')
        ->and($last->after['email'])->toEqual('email@email.test')
        ->and($first)->not()->toBeNull()
        ->and($first)->toBeInstanceOf(Audit::class)
        ->and($first->event)->toEqual('UPDATE')
        ->and($first->schema)->toEqual('public')
        ->and($first->table)->toEqual('users')
        ->and($first->before)->not()->toBeNull()
        ->and($first->before['name'])->toEqual('USER NAME')
        ->and($first->after)->not()->toBeNull()
        ->and($first->after['name'])->toEqual('USER UPDATE');
});

test('audit test in direct insertion and deleting into the database', function () {
    DB::unprepared(
        query: 'INSERT INTO public.users(name, email) VALUES (\'USER NAME\', \'email@email.test\');'
    );

    DB::unprepared(
        query: 'DELETE FROM public.users WHERE email = \'email@email.test\';'
    );

    $last = Audit::query()->orderBy('id')->first();
    $first = Audit::query()->orderByDesc('id')->first();

    expect(Audit::query()->count())->toEqual(2)
        ->and($last)->not()->toBeNull()
        ->and($last)->toBeInstanceOf(Audit::class)
        ->and($last->event)->toEqual('INSERT')
        ->and($last->schema)->toEqual('public')
        ->and($last->table)->toEqual('users')
        ->and($last->before)->toBeNull()
        ->and($last->after['name'])->toEqual('USER NAME')
        ->and($last->after['email'])->toEqual('email@email.test')
        ->and($first)->not()->toBeNull()
        ->and($first)->toBeInstanceOf(Audit::class)
        ->and($first->event)->toEqual('DELETE')
        ->and($first->schema)->toEqual('public')
        ->and($first->table)->toEqual('users')
        ->and($first->before)->not()->toBeNull()
        ->and($first->before['name'])->toEqual('USER NAME')
        ->and($first->before['email'])->toEqual('email@email.test')
        ->and($first->after)->toBeNull();
});
