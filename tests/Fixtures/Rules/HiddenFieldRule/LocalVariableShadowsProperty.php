<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class LocalVariableShadowsProperty
{
    private int $total = 0;

    public function recalculate(): void
    {
        $total = 0;
        $this->total = $total;
    }
}
