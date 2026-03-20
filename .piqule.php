<?php

declare(strict_types=1);

use Haspadar\Piqule\Config\DefaultConfig;
use Haspadar\Piqule\Config\OverrideConfig;

return new OverrideConfig(new DefaultConfig(), [
    'ci.pr.max_lines_changed' => 1000,
    'typos.exclude' => ['docs/vendors/'],
    'phpunit.php_options' => '-d memory_limit=512M',
]);
