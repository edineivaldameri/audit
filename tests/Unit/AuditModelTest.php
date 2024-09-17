<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\Audit\Models\Audit;
use Illuminate\Support\Facades\Artisan;
use Workbench\EdineiValdameri\Laravel\Audit\App\Models\User;

beforeEach(function () {
    Artisan::call('audit:install');
});

test('audit insertion test using model\'s', function () {
    $user = User::factory()->create();
    $audit = Audit::query()->first();

    expect(Audit::query()->count())->toEqual(1)
        ->and($audit)->not()->toBeNull()
        ->and($audit)->toBeInstanceOf(Audit::class)
        ->and($audit->event)->toEqual('INSERT')
        ->and($audit->schema)->toEqual('public')
        ->and($audit->table)->toEqual('users')
        ->and($audit->before)->toBeNull()
        ->and($audit->after['id'])->toEqual($user->getKey())
        ->and($audit->after['name'])->toEqual($user->name)
        ->and($audit->after['email'])->toEqual($user->email);
});

test('audit insertion and updating test using model\'s', function () {
    $user = User::factory()->create([
        'name' => 'USER NAME',
        'email' => 'email@email.com',
    ]);
    $user->update([
        'name' => 'USER UPDATE',
        'email' => 'email@update.com',
    ]);

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
        ->and($last->after['email'])->toEqual('email@email.com')
        ->and($first)->not()->toBeNull()
        ->and($first)->toBeInstanceOf(Audit::class)
        ->and($first->event)->toEqual('UPDATE')
        ->and($first->schema)->toEqual('public')
        ->and($first->table)->toEqual('users')
        ->and($first->before)->not()->toBeNull()
        ->and($first->before['name'])->toEqual('USER NAME')
        ->and($first->after)->not()->toBeNull()
        ->and($first->after['name'])->toEqual('USER UPDATE')
        ->and($first->after['email'])->toEqual('email@update.com');
});

test('audit insertion and deleting test using model\'s', function () {
    $user = User::factory()->create();
    $user->delete();

    $last = Audit::query()->orderBy('id')->first();
    $first = Audit::query()->orderByDesc('id')->first();

    expect(Audit::query()->count())->toEqual(2)
        ->and($last)->not()->toBeNull()
        ->and($last)->toBeInstanceOf(Audit::class)
        ->and($last->event)->toEqual('INSERT')
        ->and($last->schema)->toEqual('public')
        ->and($last->table)->toEqual('users')
        ->and($last->before)->toBeNull()
        ->and($last->after['id'])->toEqual($user->getKey())
        ->and($last->after['name'])->toEqual($user->name)
        ->and($last->after['email'])->toEqual($user->email)
        ->and($first)->not()->toBeNull()
        ->and($first)->toBeInstanceOf(Audit::class)
        ->and($first->event)->toEqual('DELETE')
        ->and($first->schema)->toEqual('public')
        ->and($first->table)->toEqual('users')
        ->and($first->before)->not()->toBeNull()
        ->and($first->before['id'])->toEqual($user->getKey())
        ->and($first->before['name'])->toEqual($user->name)
        ->and($first->before['email'])->toEqual($user->email)
        ->and($first->after)->toBeNull();
});
