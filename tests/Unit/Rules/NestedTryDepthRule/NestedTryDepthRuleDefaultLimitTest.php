<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedTryDepthRule;

use Haspadar\PHPStanRules\Rules\NestedTryDepthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedTryDepthRule> */
final class NestedTryDepthRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedTryDepthRule();
    }

    #[Test]
    public function reportsNestingPastDefaultLimitOfOne(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedTryDepthRule/DefaultLimitMethod.php'],
            [
                [
                    'Nested try depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedTryDepthRule\DefaultLimitMethod::run(). Maximum allowed is 1.',
                    13,
                ],
            ],
        );
    }
}
