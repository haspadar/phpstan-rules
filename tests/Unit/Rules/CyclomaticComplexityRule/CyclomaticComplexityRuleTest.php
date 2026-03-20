<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CyclomaticComplexityRule;

use Haspadar\PHPStanRules\Rules\CyclomaticComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CyclomaticComplexityRule> */
final class CyclomaticComplexityRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CyclomaticComplexityRule(3);
    }

    #[Test]
    public function passesWhenComplexityIsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CyclomaticComplexityRule/SimpleMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenComplexityExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CyclomaticComplexityRule/ComplexMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule\ComplexMethod::run() has cyclomatic complexity of 4. Maximum allowed is 3.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenComplexityIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CyclomaticComplexityRule/ExactMethod.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CyclomaticComplexityRule/SuppressedMethod.php'],
            [],
        );
    }
}
