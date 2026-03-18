<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLengthRule;

use Haspadar\PHPStanRules\Rules\MethodLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLengthRule> */
final class MethodLengthRuleSkipBlankLinesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLengthRule(20, ['skipBlankLines' => true]);
    }

    #[Test]
    public function passesWhenBlankLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/LongMethodWithBlanks.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenCodeLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/LongMethod.php'],
            [
                ['Method run() is 22 lines long. Maximum allowed is 20.', 9],
            ],
        );
    }

    #[Test]
    public function skipsLinesContainingOnlySpaces(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/LongMethodWithSpacedBlanks.php'],
            [],
        );
    }
}
