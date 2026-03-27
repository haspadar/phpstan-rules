<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ModifiedControlVariableRule;

use Haspadar\PHPStanRules\Rules\ModifiedControlVariableRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ModifiedControlVariableRule> */
final class ModifiedControlVariableRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ModifiedControlVariableRule();
    }

    #[Test]
    public function reportsErrorWhenForIncrementInBody(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithForIncrementInBody.php'],
            [
                ['For loop control variable $i must not be modified inside the loop body.', 12],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenForAssignInBody(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithForAssignInBody.php'],
            [
                ['For loop control variable $i must not be modified inside the loop body.', 12],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenForCompoundAssignInBody(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithForCompoundAssignInBody.php'],
            [
                ['For loop control variable $i must not be modified inside the loop body.', 12],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenForPreDecInBody(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithForPreDecInBody.php'],
            [
                ['For loop control variable $i must not be modified inside the loop body.', 12],
            ],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/SuppressedClass.php'],
            [],
        );
    }
}
