<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CognitiveComplexityRule;

use Haspadar\PHPStanRules\Rules\CognitiveComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CognitiveComplexityRule> */
final class CognitiveComplexityRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CognitiveComplexityRule(3);
    }

    #[Test]
    public function passesWhenWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CognitiveComplexityRule/WithinLimit.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CognitiveComplexityRule/ExceedsLimit.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule\ExceedsLimit::complex() has cognitive complexity of 4. Maximum allowed is 3.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenExactLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CognitiveComplexityRule/ExactLimit.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CognitiveComplexityRule/SuppressedClass.php'],
            [],
        );
    }
}
