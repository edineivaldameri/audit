<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Models\Builders;

use EdineiValdameri\Laravel\Audit\Models\Audit;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<Audit>
 */
class AuditBuilder extends Builder
{
    public function schema(string $schema): self
    {
        return $this->where('schema', $schema);
    }

    public function table(string $table): self
    {
        return $this->where('table', $table);
    }

    public function event(string $event): self
    {
        return $this->where('event', $event);
    }
}
