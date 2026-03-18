<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLinesRule;

use Haspadar\PHPStanRules\Rules\MethodLinesRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLinesRule> */
final class MethodLinesRuleSkipBlankLinesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLinesRule(20, ['skipBlankLines' => true]);
    }

    #[Test]
    public function passesWhenBlankLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/LongMethodWithBlanks.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenCodeLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/LongMethod.php'],
            [
                ['Method run() is 22 lines long. Maximum allowed is 20.', 9],
            ],
        );
    }

    #[Test]
    public function skipsLinesContainingOnlySpaces(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/LongMethodWithSpacedBlanks.php'],
            [],
        );
    }
}
