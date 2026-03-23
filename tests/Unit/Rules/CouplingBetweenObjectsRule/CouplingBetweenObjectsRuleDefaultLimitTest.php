<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CouplingBetweenObjectsRule;

use Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CouplingBetweenObjectsRule> */
final class CouplingBetweenObjectsRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CouplingBetweenObjectsRule();
    }

    #[Test]
    public function reportsErrorOnlyForClassExceedingDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/TooManyDefaultDependencies.php'],
            [
                ['Class TooManyDefaultDependencies has a coupling between objects value of 16. Maximum allowed is 15.', 7],
            ],
        );
    }
}
