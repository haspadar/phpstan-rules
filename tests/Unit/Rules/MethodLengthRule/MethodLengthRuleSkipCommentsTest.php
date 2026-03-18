<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLengthRule;

use Haspadar\PHPStanRules\Rules\MethodLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLengthRule> */
final class MethodLengthRuleSkipCommentsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLengthRule(20, ['skipComments' => true]);
    }

    #[Test]
    public function passesWhenCommentLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/LongMethodWithComments.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenCodeLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/LongMethod.php'],
            [
                ['Method run() is 23 lines long. Maximum allowed is 20.', 9],
            ],
        );
    }
}
