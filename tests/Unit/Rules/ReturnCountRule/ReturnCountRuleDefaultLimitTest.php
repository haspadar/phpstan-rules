<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ReturnCountRule;

use Haspadar\PHPStanRules\Rules\ReturnCountRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ReturnCountRule> */
final class ReturnCountRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ReturnCountRule();
    }

    #[Test]
    public function passesWhenMethodIsExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnCountRule/MethodWithOneReturn.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnCountRule/MethodWithDefaultLimitExceeded.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnCountRule\MethodWithDefaultLimitExceeded::find() has 2 return statements. Maximum allowed is 1.',
                    9,
                ],
            ],
        );
    }
}
