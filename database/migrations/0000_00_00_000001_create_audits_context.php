<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAuditsContext extends Migration
{
    public function up(): void
    {
        DB::unprepared(
            query: $this->getSqlForDropFunction('audit')
        );
        DB::unprepared(
            query: $this->getSqlForDropFunction('audit_context')
        );
        DB::unprepared(
            query: $this->getSqlForDropFunction('audit_enabled')
        );

        DB::unprepared(
            query: file_get_contents(
                filename: __DIR__ . '/../sqls/audit_context.sql'
            )
        );

        DB::unprepared(
            query: file_get_contents(
                filename: __DIR__ . '/../sqls/audit_enabled.sql'
            )
        );

        DB::unprepared(
            query: file_get_contents(
                filename: __DIR__ . '/../sqls/audit.sql'
            )
        );
    }

    public function down(): void
    {
        DB::unprepared(
            query: $this->getSqlForDropFunction('audit')
        );
        DB::unprepared(
            query: $this->getSqlForDropFunction('audit_context')
        );
        DB::unprepared(
            query: $this->getSqlForDropFunction('audit_enabled')
        );
    }

    /**
     * Return SQL to drop a function.
     *
     * @param  string  $function
     */
    private function getSqlForDropFunction($function): string
    {
        return "DROP FUNCTION IF EXISTS {$function}();";
    }
}
