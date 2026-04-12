<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ConstantUsageRule;

use Haspadar\PHPStanRules\Rules\ConstantUsageRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ConstantUsageRule> */
final class ConstantUsageRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ConstantUsageRule([
            'ignoreNumbers' => [0, 1],
            'checkStrings' => true,
            'ignoreStrings' => [''],
        ]);
    }

    #[Test]
    public function reportsErrorWhenMagicNumberFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithMagicNumbers.php'],
            [
                ['Magic number 42 found. Define a named constant instead.', 11],
                ['Magic number 3.14 found. Define a named constant instead.', 16],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenMagicStringFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithMagicStrings.php'],
            [
                ['Magic string "active" found. Define a named constant instead.', 11],
                ['Magic string "admin" found. Define a named constant instead.', 16],
            ],
        );
    }

    #[Test]
    public function passesWhenLiteralsAreInConstants(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithConstants.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenNumbersAreIgnored(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithIgnoredNumbers.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenStringsAreArrayKeys(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithArrayKeys.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenLiteralsAreInAttributes(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithAttributes.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenEmptyStringIsIgnored(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithEmptyString.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/SuppressedClass.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenLiteralsAreParameterDefaults(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithParameterDefaults.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenNegativeMagicNumberFound(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithNegativeNumber.php'],
            [
                ['Magic number -42 found. Define a named constant instead.', 11],
            ],
        );
    }

    #[Test]
    public function passesWhenNegativeNumberIsParameterDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithNegativeParameterDefault.php'],
            [],
        );
    }
}
