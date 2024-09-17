<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Listeners;

use EdineiValdameri\Laravel\Audit\Concerns\AuditTrigger;
use Illuminate\Support\Facades\DB;

class MigrationEndedListener
{
    use AuditTrigger;

    public function handle(): void
    {
        DB::listen(function ($query) {
            if (stripos($query->sql, 'create table') !== false) {
                preg_match('/create\s+table\s+["`]?([a-zA-Z0-9_]+)["`]?/i', $query->sql, $matches);
                if (isset($matches[1])) {
                    $table = $matches[1];

                    if (
                        !in_array($table, $this->getSkippedTables(), true) &&
                        config('audit.enabled', true)
                    ) {
                        $this->createAuditTrigger($table);
                    }
                }
            }
        });
    }
}
