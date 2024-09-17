<?php

declare(strict_types=1);

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'die', 'exit', 'ray', 'var_export', 'var_dump'])
    ->each->not->toBeUsed();
