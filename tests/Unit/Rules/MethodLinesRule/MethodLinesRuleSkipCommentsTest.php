<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLinesRule;

use Haspadar\PHPStanRules\Rules\MethodLinesRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLinesRule> */
final class MethodLinesRuleSkipCommentsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLinesRule(20, ['skipComments' => true]);
    }

    #[Test]
    public function passesWhenCommentLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/LongMethodWithComments.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenCodeLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/LongMethod.php'],
            [
                ['Method run() is 23 lines long. Maximum allowed is 20.', 9],
            ],
        );
    }
}
