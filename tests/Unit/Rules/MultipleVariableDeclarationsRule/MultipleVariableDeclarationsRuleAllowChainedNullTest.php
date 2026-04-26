<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MultipleVariableDeclarationsRule;

use Haspadar\PHPStanRules\Rules\MultipleVariableDeclarationsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MultipleVariableDeclarationsRule> */
final class MultipleVariableDeclarationsRuleAllowChainedNullTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MultipleVariableDeclarationsRule(['allowChainedNull' => true]);
    }

    #[Test]
    public function passesChainedNullAssignmentWhenOptionIsEnabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/ChainedNullAssignment.php'],
            [],
        );
    }

    #[Test]
    public function reportsChainedNonNullAssignmentEvenWhenOptionIsEnabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/ChainedAssignment.php'],
            [
                ['Chained assignment is forbidden: split into separate statements.', 12],
                ['Chained assignment is forbidden: split into separate statements.', 14],
            ],
        );
    }

    #[Test]
    public function continuesAfterAcceptedNullChainToInspectLaterChains(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/MixedChains.php'],
            [
                ['Chained assignment is forbidden: split into separate statements.', 12],
            ],
        );
    }
}
