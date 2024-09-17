<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Tests;

use EdineiValdameri\Laravel\Audit\Providers\AuditServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\EdineiValdameri\Laravel\Audit\App\Providers\WorkbenchServiceProvider;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        /** @phpstan-ignore-next-line */
        Factory::guessFactoryNamesUsing(function (string $model) {
            $namespaces = [
                'EdineiValdameri\\Laravel\\Audit\\Database\\Factories\\' . class_basename($model) . 'Factory',
                'Workbench\\EdineiValdameri\\Laravel\\Audit\\Database\\Factories\\' . class_basename($model) . 'Factory',
            ];

            foreach ($namespaces as $option) {
                if (class_exists($option)) {
                    return $option;
                }
            }

            return $model;
        });

        /** @phpstan-ignore-next-line */
        Factory::guessModelNamesUsing(function (string $factory) {
            $namespaces = [
                'EdineiValdameri\\Laravel\\Audit\\Models\\' . Str::replaceLast('Factory', '', class_basename($factory)),
                'Workbench\\EdineiValdameri\\Laravel\\Audit\\App\\Models\\' . Str::replaceLast('Factory', '', class_basename($factory)),
            ];

            foreach ($namespaces as $option) {
                if (class_exists($option)) {
                    return $option;
                }
            }

            return $factory;
        });
    }

    public function getEnvironmentSetUp($app): void
    {
    }

    protected function getPackageProviders($app): array
    {
        return [
            WorkbenchServiceProvider::class,
            AuditServiceProvider::class,
        ];
    }
}
