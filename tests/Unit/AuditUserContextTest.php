<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\Audit\Models\Audit;
use Illuminate\Support\Facades\Artisan;
use Workbench\EdineiValdameri\Laravel\Audit\App\Models\User;

beforeEach(function () {
    Artisan::call('audit:install');
});

test('user testing in the context of auditing', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $newUser = User::factory()->create();

    $audit = Audit::query()->orderByDesc('id')->first();

    expect(Audit::query()->count())->toEqual(2)
        ->and($audit)->not()->toBeNull()
        ->and($audit)->toBeInstanceOf(Audit::class)
        ->and($audit->event)->toEqual('INSERT')
        ->and($audit->schema)->toEqual('public')
        ->and($audit->table)->toEqual('users')
        ->and($audit->before)->toBeNull()
        ->and($audit->after['id'])->toEqual($newUser->getKey())
        ->and($audit->after['name'])->toEqual($newUser->name)
        ->and($audit->after['email'])->toEqual($newUser->email)
        ->and($audit->context['user_id'])->toEqual($user->getKey())
        ->and($audit->context['user_name'])->toEqual($user->name);
});
