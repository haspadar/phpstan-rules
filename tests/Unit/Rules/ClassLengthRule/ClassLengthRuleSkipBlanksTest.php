<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ClassLengthRule;

use Haspadar\PHPStanRules\Rules\ClassLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ClassLengthRule> */
final class ClassLengthRuleSkipBlanksTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ClassLengthRule(10, ['skipBlankLines' => true]);
    }

    #[Test]
    public function passesWhenBlankLinesSkipped(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClassWithBlanks.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassExceedsLimitEvenWithBlankLinesSkipped(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongClassWithFewBlanks.php'],
            [
                ['Class LongClassWithFewBlanks is 12 lines long. Maximum allowed is 10.', 7],
            ],
        );
    }
}
