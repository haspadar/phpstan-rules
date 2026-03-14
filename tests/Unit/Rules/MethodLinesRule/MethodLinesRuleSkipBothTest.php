<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLinesRule;

use Haspadar\PHPStanRules\Rules\MethodLinesRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLinesRule> */
final class MethodLinesRuleSkipBothTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLinesRule(['maxLines' => 20, 'skipBlankLines' => true, 'skipComments' => true]);
    }

    #[Test]
    public function passesWhenBlanksAndCommentsExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/LongMethodWithBlanksAndComments.php'],
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
}
