<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ThrowsCountRule;

use Haspadar\PHPStanRules\Rules\ThrowsCountRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ThrowsCountRule> */
final class ThrowsCountRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ThrowsCountRule();
    }

    #[Test]
    public function reportsWhenMethodExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ThrowsCountRule/DefaultLimit.php'],
            [
                [
                    'Method run() declares 2 @throws types. Maximum allowed is 1.',
                    13,
                ],
            ],
        );
    }
}
