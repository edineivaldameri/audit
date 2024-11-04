<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\Audit\Models\Audit;
use Illuminate\Support\Facades\Artisan;
use Workbench\EdineiValdameri\Laravel\Audit\App\Models\User;

beforeEach(function () {
    Artisan::call('audit:install');
});

it('can list audit with pagination', function () {
    User::factory()->count(30)->create();

    $response = $this->getJson(route('audits.index'));

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

    $response = $this->getJson(route('audits.index', [
        'schema' => 'schema',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');

    $response = $this->getJson(route('audits.index', [
        'schema' => 'public',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(5, 'data');
});

it('can list audit with pagination and table filter', function () {
    User::factory()->count(5)->create();

    $response = $this->getJson(route('audits.index', [
        'table' => 'table',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');

    $response = $this->getJson(route('audits.index', [
        'table' => 'users',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(5, 'data');
});

it('can list audit with pagination and event filter', function () {
    User::factory()->count(5)->create();

    $response = $this->getJson(route('audits.index', [
        'event' => 'event',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');

    $response = $this->getJson(route('audits.index', [
        'event' => 'INSERT',
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(5, 'data');
});

it('can fetch a single audit', function () {
    User::factory()->create();

    /** @var Audit $audit */
    $audit = Audit::query()->first();

    $response = $this->getJson(route('audits.show', $audit->getKey()));

    $response->assertStatus(200)
        ->assertJson([
            'id' => $audit->getKey(),
            'schema' => $audit->schema,
            'table' => $audit->table,
            'event' => $audit->event,
            'after' => $audit->after,
            'context' => $audit->context,
            'before' => $audit->before,
        ]);
});

it('returns 404 if the audit is not found', function () {
    $response = $this->getJson(route('audits.show', 999));

    $response->assertStatus(404);
});

it('returns the correct structure for a single audit', function () {
    User::factory()->create();
    $audit = Audit::query()->first();

    $response = $this->getJson(route('audits.show', $audit->getKey()));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'schema',
            'table',
            'event',
            'after',
            'context',
            'before',
        ]);
});
