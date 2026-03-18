<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLinesRule;

use Haspadar\PHPStanRules\Rules\MethodLinesRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLinesRule> */
final class MethodLinesRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLinesRule();
    }

    #[Test]
    public function passesWhenMethodIsExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/ExactDefaultMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/LongDefaultMethod.php'],
            [
                ['Method run() is 101 lines long. Maximum allowed is 100.', 9],
            ],
        );
    }
}
