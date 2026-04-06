<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CognitiveComplexityRule;

use Haspadar\PHPStanRules\Rules\CognitiveComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CognitiveComplexityRule> */
final class CognitiveComplexityRuleNestingTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CognitiveComplexityRule(5);
    }

    #[Test]
    public function reportsErrorWhenNestingPenaltyExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CognitiveComplexityRule/NestingPenalty.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule\NestingPenalty::deeplyNested() has cognitive complexity of 6. Maximum allowed is 5.',
                    9,
                ],
            ],
        );
    }
}
