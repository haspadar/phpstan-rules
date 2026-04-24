<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class LocalNotMatchingProperty
{
    private int $total = 0;

    public function recompute(): void
    {
        $sum = 0;
        $this->total = $sum;
    }
}
