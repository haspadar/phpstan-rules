<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\StringLiteralsConcatenationRule;

use Haspadar\PHPStanRules\Rules\StringLiteralsConcatenationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<StringLiteralsConcatenationRule> */
final class StringLiteralsConcatenationRuleAllowMixedTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new StringLiteralsConcatenationRule(['allowMixed' => true]);
    }

    #[Test]
    public function passesWhenMixedConcatAllowed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/ClassWithAllowedMixed.php'],
            [],
        );
    }

    #[Test]
    public function reportsLiteralConcatEvenWhenMixedAllowed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/ClassWithLiteralConcat.php'],
            [
                ['String literal concatenation found on line 11. Use sprintf() or string interpolation instead.', 11],
            ],
        );
    }

    #[Test]
    public function passesWhenConcatAssignMixedAllowed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StringLiteralsConcatenationRule/ClassWithConcatAssignMixed.php'],
            [],
        );
    }
}
