<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class DuplicateLocalAssignments
{
    private int $total = 0;

    public function recompute(): void
    {
        $total = 0;
        $total = 10;
        $this->total = $total;
    }
}
