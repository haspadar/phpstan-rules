<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CouplingBetweenObjectsRule;

use Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CouplingBetweenObjectsRule> */
final class CouplingBetweenObjectsRuleExcludedFirstTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CouplingBetweenObjectsRule(0, ['excludedClasses' => [
            'Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule\TypeA',
        ]]);
    }

    #[Test]
    public function countsTypesAfterExcludedOne(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ClassWithExcludedDependencyFirst.php'],
            [
                ['Class ClassWithExcludedDependencyFirst has a coupling between objects value of 2. Maximum allowed is 0.', 7],
            ],
        );
    }
}
