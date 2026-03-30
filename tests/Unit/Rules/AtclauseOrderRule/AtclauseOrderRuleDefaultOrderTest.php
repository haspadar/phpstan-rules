<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\AtclauseOrderRule;

use Haspadar\PHPStanRules\Rules\AtclauseOrderRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<AtclauseOrderRule> */
final class AtclauseOrderRuleDefaultOrderTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new AtclauseOrderRule();
    }

    #[Test]
    public function reportsErrorWhenDefaultOrderIsViolated(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithWrongOrder.php'],
            [
                ['PHPDoc tag @return must come before @throws in save().', 18],
            ],
        );
    }
}
