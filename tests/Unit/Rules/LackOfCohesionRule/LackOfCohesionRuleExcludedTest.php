<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleExcludedTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule(1, [
            'minMethods' => 4,
            'minProperties' => 2,
            'excludedClasses' => [
                'Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\LackOfCohesionRule\\ExcludedClass',
            ],
        ]);
    }

    #[Test]
    public function skipsClassesListedInExcludedClasses(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/ExcludedClass.php'],
            [],
            'classes listed in excludedClasses must be skipped regardless of their LCOM value',
        );
    }
}
