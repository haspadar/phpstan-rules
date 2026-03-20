<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CyclomaticComplexityRule;

use Haspadar\PHPStanRules\Rules\CyclomaticComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CyclomaticComplexityRule> */
final class CyclomaticComplexityRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CyclomaticComplexityRule();
    }

    #[Test]
    public function passesWhenComplexityIsExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CyclomaticComplexityRule/DefaultLimitMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenComplexityExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CyclomaticComplexityRule/DefaultLimitExceeded.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule\DefaultLimitExceeded::run() has cyclomatic complexity of 11. Maximum allowed is 10.',
                    9,
                ],
            ],
        );
    }
}
