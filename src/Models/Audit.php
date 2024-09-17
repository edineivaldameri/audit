<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Models;

use EdineiValdameri\Laravel\Audit\Models\Builders\AuditBuilder;
use Illuminate\Database\Eloquent\HasBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $event
 * @property string $schema
 * @property string $table
 * @property array $before
 * @property array $context
 * @property array $after
 * @property array<string, string> $casts
 */
class Audit extends Model
{
    /** @use HasBuilder<AuditBuilder> */
    use HasBuilder;

    public $timestamps = false;

    protected static string $builder = AuditBuilder::class;

    protected $table = 'public.audits';

    protected $casts = [
        'context' => 'array',
        'before' => 'array',
        'after' => 'array',
        'date' => 'datetime',
    ];
}
