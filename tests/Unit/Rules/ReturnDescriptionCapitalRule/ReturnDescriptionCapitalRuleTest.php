<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ReturnDescriptionCapitalRule;

use Haspadar\PHPStanRules\Rules\ReturnDescriptionCapitalRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ReturnDescriptionCapitalRule> */
final class ReturnDescriptionCapitalRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ReturnDescriptionCapitalRule();
    }

    #[Test]
    public function passesWhenReturnDescriptionStartsWithCapital(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnDescriptionCapitalRule/MethodWithReturnDescription.php'],
            [],
            'Capital letter in @return description should pass',
        );
    }

    #[Test]
    public function reportsErrorWhenReturnDescriptionStartsWithLowercase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnDescriptionCapitalRule/MethodWithLowercaseReturn.php'],
            [
                ['@return description for getName() must start with a capital letter.', 14],
            ],
            '@return description starting with lowercase must be reported',
        );
    }

    #[Test]
    public function passesWhenReturnHasNoDescription(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnDescriptionCapitalRule/MethodWithoutReturnDescription.php'],
            [],
            '@return without description should pass',
        );
    }

    #[Test]
    public function passesWhenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnDescriptionCapitalRule/MethodWithNoPhpDoc.php'],
            [],
            'Method without PHPDoc should pass',
        );
    }

    #[Test]
    public function passesWhenMethodIsInInterface(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnDescriptionCapitalRule/InterfaceMethod.php'],
            [],
            'Interface method should be skipped',
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnDescriptionCapitalRule/SuppressedMethod.php'],
            [],
            'Suppressed error should pass',
        );
    }
}
