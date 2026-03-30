<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\AtclauseOrderRule;

use Haspadar\PHPStanRules\Rules\AtclauseOrderRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<AtclauseOrderRule> */
final class AtclauseOrderRuleCustomOrderTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new AtclauseOrderRule(['tagOrder' => ['@throws', '@param', '@return']]);
    }

    #[Test]
    public function passesWhenTagsMatchCustomOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithThrowsFirst.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenDefaultOrderViolatesCustomConfig(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithCorrectOrder.php'],
            [
                ['PHPDoc tag @throws must come before @return in save().', 18],
            ],
        );
    }
}
