<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\InnerAssignmentRule;

use Haspadar\PHPStanRules\Rules\InnerAssignmentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<InnerAssignmentRule> */
final class InnerAssignmentRuleLoopIdiomTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new InnerAssignmentRule();
    }

    #[Test]
    public function passesWhileLoopIdiom(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/ClassWithWhileLoopIdiom.php'],
            [],
        );
    }

    #[Test]
    public function passesDoWhileLoopIdiom(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/ClassWithDoWhileLoopIdiom.php'],
            [],
        );
    }

    #[Test]
    public function passesForLoopIdiom(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/ClassWithForLoopIdiom.php'],
            [],
        );
    }
}
