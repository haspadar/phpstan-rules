<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithForPreDecInBody
{
    public function process(): void
    {
        for ($i = 10; $i > 0; $i--) {
            --$i;
        }
    }
}
