<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLengthRule;

use Haspadar\PHPStanRules\Rules\MethodLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLengthRule> */
final class MethodLengthRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLengthRule(20);
    }

    #[Test]
    public function passesWhenMethodFitsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/ShortMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/LongMethod.php'],
            [
                ['Method run() is 23 lines long. Maximum allowed is 20.', 9],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/ExactMethod.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/SuppressedLongMethod.php'],
            [],
        );
    }

    #[Test]
    public function countsCommentLinesWhenSkipCommentsNotEnabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/LongMethodWithComments.php'],
            [
                ['Method run() is 24 lines long. Maximum allowed is 20.', 9],
            ],
        );
    }
}
