<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ModifiedControlVariableRule;

use Haspadar\PHPStanRules\Rules\ModifiedControlVariableRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ModifiedControlVariableRule> */
final class ModifiedControlVariableRuleForeachTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ModifiedControlVariableRule();
    }

    #[Test]
    public function reportsErrorWhenForeachVariableModified(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithForeachModification.php'],
            [
                ['Foreach loop control variable $item must not be modified inside the loop body.', 13],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenForeachKeyModified(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithForeachKeyModification.php'],
            [
                ['Foreach loop control variable $key must not be modified inside the loop body.', 13],
            ],
        );
    }
}
