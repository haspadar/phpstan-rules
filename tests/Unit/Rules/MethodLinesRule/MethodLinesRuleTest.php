<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLinesRule;

use Haspadar\PHPStanRules\Rules\MethodLinesRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLinesRule> */
final class MethodLinesRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLinesRule(['maxLines' => 20]);
    }

    #[Test]
    public function passesWhenMethodFitsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/ShortMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/LongMethod.php'],
            [
                ['Method run() is 23 lines long. Maximum allowed is 20.', 9],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/ExactMethod.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/SuppressedLongMethod.php'],
            [],
        );
    }
}
