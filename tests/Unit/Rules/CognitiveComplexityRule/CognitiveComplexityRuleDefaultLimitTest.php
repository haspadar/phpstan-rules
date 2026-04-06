<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CognitiveComplexityRule;

use Haspadar\PHPStanRules\Rules\CognitiveComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CognitiveComplexityRule> */
final class CognitiveComplexityRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CognitiveComplexityRule();
    }

    #[Test]
    public function reportsErrorWhenExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CognitiveComplexityRule/ExceedsDefaultLimit.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule\ExceedsDefaultLimit::complex() has cognitive complexity of 13. Maximum allowed is 10.',
                    9,
                ],
            ],
        );
    }
}
