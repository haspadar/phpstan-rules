<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\IfThenThrowElseRule;

use Haspadar\PHPStanRules\Rules\IfThenThrowElseRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<IfThenThrowElseRule> */
final class IfThenThrowElseRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new IfThenThrowElseRule();
    }

    #[Test]
    public function reportsElseBranchAfterThrow(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IfThenThrowElseRule/ElseAfterThrow.php'],
            [
                [
                    'Remove the else branch — the if block always throws.',
                    11,
                ],
            ],
        );
    }

    #[Test]
    public function reportsElseIfBranchAfterThrow(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IfThenThrowElseRule/ElseIfAfterThrow.php'],
            [
                [
                    'Remove the else branch — the if block always throws.',
                    11,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenNoElseAfterThrow(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IfThenThrowElseRule/NoElse.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenElseAfterReturn(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IfThenThrowElseRule/ElseAfterReturn.php'],
            [],
        );
    }

    #[Test]
    public function suppressesViolationWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IfThenThrowElseRule/SuppressedClass.php'],
            [],
        );
    }
}
