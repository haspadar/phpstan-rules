<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedForDepthRule;

use Haspadar\PHPStanRules\Rules\NestedForDepthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedForDepthRule> */
final class NestedForDepthRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedForDepthRule();
    }

    #[Test]
    public function reportsNestingPastDefaultLimitOfOne(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/DefaultLimitMethod.php'],
            [
                [
                    'Nested loop depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule\DefaultLimitMethod::flatten(). Maximum allowed is 1.',
                    14,
                ],
            ],
        );
    }
}
