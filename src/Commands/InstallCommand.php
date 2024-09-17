<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Commands;

use EdineiValdameri\Laravel\Audit\Concerns\AuditTrigger;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use AuditTrigger;

    protected $signature = 'audit:install';

    protected $description = 'Command to install auditing on all tables';

    public function handle(): void
    {
        $this->createAuditTriggers();
    }
}
