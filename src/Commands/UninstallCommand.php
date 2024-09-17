<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\Audit\Commands;

use EdineiValdameri\Laravel\Audit\Concerns\AuditTrigger;
use Illuminate\Console\Command;

class UninstallCommand extends Command
{
    use AuditTrigger;

    protected $signature = 'audit:uninstall';

    protected $description = 'Command to uninstall auditing on all tables';

    public function handle(): void
    {
        $this->dropAuditTriggers();
    }
}
