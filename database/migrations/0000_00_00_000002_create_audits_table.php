<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditsTable extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('schema', 100);
            $table->string('table', 150);
            $table->string('event', 100);
            $table->jsonb('context')->nullable();
            $table->jsonb('before')->nullable();
            $table->jsonb('after')->nullable();
            $table->timestamp('date');
        });
    }

    public function down(): void
    {
        Schema::drop('audits');
    }
}
