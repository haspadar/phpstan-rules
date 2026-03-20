<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CyclomaticComplexityRule;

use Haspadar\PHPStanRules\Rules\CyclomaticComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CyclomaticComplexityRule> */
final class CyclomaticComplexityRuleBranchingTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CyclomaticComplexityRule(2);
    }

    #[Test]
    public function countsSwitchCaseArms(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CyclomaticComplexityRule/MethodWithSwitch.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule\MethodWithSwitch::run() has cyclomatic complexity of 3. Maximum allowed is 2.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function countsMatchArms(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CyclomaticComplexityRule/MethodWithMatch.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule\MethodWithMatch::run() has cyclomatic complexity of 3. Maximum allowed is 2.',
                    9,
                ],
            ],
        );
    }
}
