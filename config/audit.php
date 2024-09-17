<?php

declare(strict_types=1);

return [
    'enabled' => env('AUDIT_ENABLED', true),
    'skip' => [
        'audits',
        'public.audits',
        'migrations',
        'public.migrations',
    ],
];
