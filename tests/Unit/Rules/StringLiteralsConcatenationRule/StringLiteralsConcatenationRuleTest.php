<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\StringLiteralsConcatenationRule;

use Haspadar\PHPStanRules\Rules\StringLiteralsConcatenationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<StringLiteralsConcatenationRule> */
final class StringLiteralsConcatenationRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new StringLiteralsConcatenationRule();
    }

    #[Test]
    public function reportsErrorWhenLiteralsConcatenated(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/ClassWithLiteralConcat.php'],
            [
                ['String literal concatenation found on line 11. Use sprintf() or string interpolation instead.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenMixedConcatenation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/ClassWithMixedConcat.php'],
            [
                ['String literal concatenation found on line 11. Use sprintf() or string interpolation instead.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenConcatAssignUsed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/ClassWithConcatAssign.php'],
            [
                ['String literal concatenation found on line 12. Use sprintf() or string interpolation instead.', 12],
            ],
        );
    }

    #[Test]
    public function reportsOneErrorForChainedConcat(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/ClassWithChainedConcat.php'],
            [
                ['String literal concatenation found on line 11. Use sprintf() or string interpolation instead.', 11],
            ],
        );
    }

    #[Test]
    public function passesWhenVariablesConcatenated(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/ClassWithVariableConcat.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenSprintfUsed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/ClassWithSprintfUsage.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/SuppressedClass.php'],
            [],
        );
    }
}
