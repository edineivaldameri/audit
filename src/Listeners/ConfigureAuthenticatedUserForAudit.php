<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;

class ConfigureAuthenticatedUserForAudit
{
    public function __construct(
        private Connection $connection,
        private Request $request
    ) {
    }

    public function handle(Authenticated $event): void
    {
        $pdo = $this->connection->getPdo();

        $enabled = config('audit.enabled', true) ? 'true' : 'false';

        $context = json_encode([
            'user_id' => $event->user->getKey(),
            'user_name' => $event->user->name, //@phpstan-ignore-line
            'origin' => $this->request->fullUrl(),
            'ip' => $this->request->ip(),
        ], JSON_HEX_APOS | JSON_HEX_QUOT);

        $pdo->exec("SET \"audit.enabled\" = {$enabled};");
        $pdo->exec("SET \"audit.context\" = '{$context}';");
    }
}
