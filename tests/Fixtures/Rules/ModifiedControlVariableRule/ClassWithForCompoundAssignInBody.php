<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class ClassWithForCompoundAssignInBody
{
    public function process(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $i += 2;
        }
    }
}
