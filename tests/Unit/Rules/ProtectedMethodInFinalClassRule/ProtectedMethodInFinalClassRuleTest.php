<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ProtectedMethodInFinalClassRule;

use Haspadar\PHPStanRules\Rules\ProtectedMethodInFinalClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ProtectedMethodInFinalClassRule> */
final class ProtectedMethodInFinalClassRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ProtectedMethodInFinalClassRule();
    }

    #[Test]
    public function reportsErrorForProtectedMethodInFinalClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProtectedMethodInFinalClassRule/FinalClassWithProtectedMethod.php'],
            [
                [
                    'Method FinalClassWithProtectedMethod::query() is protected in a final class. Use private instead.',
                    7,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodIsPrivateInFinalClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProtectedMethodInFinalClassRule/FinalClassWithPrivateMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenClassIsNotFinal(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProtectedMethodInFinalClassRule/NonFinalClassWithProtectedMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsEachProtectedMethodIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProtectedMethodInFinalClassRule/FinalClassWithMultipleProtectedMethods.php'],
            [
                [
                    'Method FinalClassWithMultipleProtectedMethods::first() is protected in a final class. Use private instead.',
                    7,
                ],
                [
                    'Method FinalClassWithMultipleProtectedMethods::second() is protected in a final class. Use private instead.',
                    7,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProtectedMethodInFinalClassRule/SuppressedFinalClassWithProtectedMethod.php'],
            [],
        );
    }
}
