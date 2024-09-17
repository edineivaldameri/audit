<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Providers;

use EdineiValdameri\Laravel\Audit\Commands\InstallCommand;
use EdineiValdameri\Laravel\Audit\Commands\UninstallCommand;
use EdineiValdameri\Laravel\Audit\Listeners\ConfigureAuthenticatedUserForAudit;
use EdineiValdameri\Laravel\Audit\Listeners\MigrationEndedListener;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Database\Events\MigrationEnded;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/audit.php');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom([
                __DIR__ . '/../../database/migrations',
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/audit.php' => config_path('audit.php'),
        ], 'config');

        $this->commands([
            InstallCommand::class,
            UninstallCommand::class,
        ]);

        Event::listen(Authenticated::class, ConfigureAuthenticatedUserForAudit::class);
        Event::listen(MigrationEnded::class, MigrationEndedListener::class);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/audit.php', 'audit');
    }
}
