<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\StatementCountRule;

use Haspadar\PHPStanRules\Rules\StatementCountRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<StatementCountRule> */
final class StatementCountRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new StatementCountRule();
    }

    #[Test]
    public function passesWhenMethodIsWithinDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/DefaultLimitMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/DefaultLimitExceeded.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule\DefaultLimitExceeded::run() has 31 executable statements. Maximum allowed is 30.',
                    9,
                ],
            ],
        );
    }
}
