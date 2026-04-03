<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\AtclauseOrderRule;

use Haspadar\PHPStanRules\Rules\AtclauseOrderRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Verifies that tag names without the @ prefix are normalized correctly.
 *
 * @extends RuleTestCase<AtclauseOrderRule>
 */
final class AtclauseOrderRuleNoPrefixTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new AtclauseOrderRule(['tagOrder' => ['param', 'throws', 'return']]);
    }

    #[Test]
    public function passesWhenTagsFollowNormalizedOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithWrongOrder.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenTagsViolateNormalizedOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithCorrectOrder.php'],
            [
                ['PHPDoc tag @throws must come before @return in save().', 18],
            ],
        );
    }
}
