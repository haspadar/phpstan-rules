<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLengthRule;

use Haspadar\PHPStanRules\Rules\MethodLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Verifies that only blank lines are skipped, not the code lines that follow them
 *
 * @extends RuleTestCase<MethodLengthRule>
 */
final class MethodLengthRuleSkipBlankLinesVariantsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLengthRule(18, ['skipBlankLines' => true]);
    }

    #[Test]
    public function countsMethodSignatureLineWhenPrecededByBlank(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/ExactMethodWithLeadingBlank.php'],
            [
                ['Method run() is 19 lines long. Maximum allowed is 18.', 10],
            ],
        );
    }
}
