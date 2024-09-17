<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Workbench\EdineiValdameri\Laravel\Audit\App\Models\User;

beforeEach(function () {
    Artisan::call('audit:install');
});

it('can list audit with pagination', function () {
    User::factory()->count(30)->create();

    $response = $this->getJson('/audits');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id',  'schema', 'table', 'event', 'context', 'before', 'after', 'date'],
            ],
            'links',
            'total',
            'per_page',
            'current_page',
            'from',
            'to',
            'last_page',
        ]);
});

it('can list audit with pagination and schema filter', function () {
    User::factory()->count(5)->create();

    $response = $this->getJson('/audits?schema=schema');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');

    $response = $this->getJson('/audits?schema=public');

    $response->assertStatus(200)
        ->assertJsonCount(5, 'data');
});

it('can list audit with pagination and table filter', function () {
    User::factory()->count(5)->create();

    $response = $this->getJson('/audits?table=table');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');

    $response = $this->getJson('/audits?table=users');

    $response->assertStatus(200)
        ->assertJsonCount(5, 'data');
});

it('can list audit with pagination and event filter', function () {
    User::factory()->count(5)->create();

    $response = $this->getJson('/audits?event=event');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');

    $response = $this->getJson('/audits?event=INSERT');

    $response->assertStatus(200)
        ->assertJsonCount(5, 'data');
});
