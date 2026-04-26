<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedIfDepthRule;

use Haspadar\PHPStanRules\Rules\NestedIfDepthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedIfDepthRule> */
final class NestedIfDepthRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedIfDepthRule();
    }

    #[Test]
    public function reportsNestingPastDefaultLimitOfOne(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedIfDepthRule/DefaultLimitMethod.php'],
            [
                [
                    'Nested if depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule\DefaultLimitMethod::run(). Maximum allowed is 1.',
                    13,
                ],
            ],
        );
    }
}
