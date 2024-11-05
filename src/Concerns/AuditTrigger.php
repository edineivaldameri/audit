<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait AuditTrigger
{
    /**
     * Create audit trigger for table.
     */
    public function createAuditTrigger(string $table): void
    {
        DB::unprepared(
            query: $this->getSqlForCreateAuditTrigger($table)
        );
    }

    /**
     * Create all audit triggers.
     */
    public function createAuditTriggers(): void
    {
        foreach ($this->getAuditedTables() as $table) {
            $this->dropAuditTrigger($table);
            $this->createAuditTrigger($table);
        }
    }

    /**
     * Drop all audit triggers.
     */
    public function dropAuditTriggers(): void
    {
        foreach ($this->getAuditedTables() as $table) {
            $this->dropAuditTrigger($table);
        }
    }

    /**
     * Return the normalized table name
     */
    private function getNormalizeTableName(string $table): string
    {
        return str_replace(['.'], ['_'], $table);
    }

    /**
     * Return not audited tables.
     *
     * @return array<int, string>
     */
    private function getSkippedTables(): array
    {
        /** @var array<int, string> $return */
        $return = config('audit.skip', [
            'audits',
            'public.audits',
            'migrations',
            'public.migrations',
        ]);

        return $return;
    }

    /**
     * Return audited tables.
     *
     * @return array<int, string>
     */
    private function getAuditedTables(): array
    {
        /** @var array<int, string> $schemas */
        $schemas = config('audit.schemas', ['public']);
        $schemas = implode(', ', array_map(function ($schema) {
            /** @var string $schema */
            return "'{$schema}'";
        }, $schemas));

        $tables = DB::select('SELECT table_schema, table_name FROM information_schema.tables WHERE table_type = \'BASE TABLE\' AND table_schema IN (' . $schemas . ');');

        $return = [];
        foreach ($tables as $table) {
            if (in_array($table->table_name, $this->getSkippedTables(), true)) {
                continue;
            }

            $return[] = $table->table_schema . '.' . $table->table_name;
        }

        return $return;
    }

    /**
     * Return create audit trigger SQL to the table.
     */
    private function getSqlForCreateAuditTrigger(string $table): string
    {
        $trigger = Str::slug($this->getNormalizeTableName($table), '_') . '_audit';

        return <<<SQL
            CREATE OR REPLACE TRIGGER {$trigger}
            AFTER INSERT OR UPDATE OR DELETE ON {$table}
            FOR EACH ROW EXECUTE PROCEDURE public.audit();
            SQL;
    }

    /**
     * Return drop audit trigger SQL to the table.
     */
    private function getSqlForDropAuditTrigger(string $table): string
    {
        $trigger = Str::slug($this->getNormalizeTableName($table), '_') . '_audit';

        return <<<SQL
            DROP TRIGGER IF EXISTS {$trigger} ON {$table};
            SQL;
    }

    /**
     * Drop audit trigger from table.
     */
    private function dropAuditTrigger(string $table): void
    {
        DB::unprepared(
            query: $this->getSqlForDropAuditTrigger($table)
        );
    }
}
