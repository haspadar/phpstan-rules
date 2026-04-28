<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ThrowsCountRule;

use Haspadar\PHPStanRules\Rules\ThrowsCountRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ThrowsCountRule> */
final class ThrowsCountRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ThrowsCountRule(2);
    }

    #[Test]
    public function passesWhenThrowsCountIsBelowLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ThrowsCountRule/FewThrows.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenThrowsCountIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ThrowsCountRule/ExactThrows.php'],
            [],
        );
    }

    #[Test]
    public function reportsWhenThrowsCountExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ThrowsCountRule/TooManyThrows.php'],
            [
                [
                    'Method run() declares 3 @throws types. Maximum allowed is 2.',
                    14,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenNoThrowsPresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ThrowsCountRule/NoThrows.php'],
            [],
        );
    }

    #[Test]
    public function suppressesViolationWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ThrowsCountRule/SuppressedClass.php'],
            [],
        );
    }
}
