<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CognitiveComplexityRule;

use Haspadar\PHPStanRules\Rules\CognitiveComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CognitiveComplexityRule> */
final class CognitiveComplexityRuleStructuresTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CognitiveComplexityRule(10);
    }

    #[Test]
    public function reportsErrorForAllStructures(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CognitiveComplexityRule/AllStructures.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule\AllStructures::allStructures() has cognitive complexity of 15. Maximum allowed is 10.',
                    14,
                ],
            ],
        );
    }
}
