<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MissingThrowsRule;

use Haspadar\PHPStanRules\Rules\MissingThrowsRule;
use Override;
use PHPStan\Rules\Exceptions\MissingCheckedExceptionInThrowsCheck;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MissingThrowsRule> */
final class MissingThrowsRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new MissingThrowsRule(
            self::getContainer()->getByType(MissingCheckedExceptionInThrowsCheck::class),
            ['skipOverridden' => true],
        );
    }

    #[Test]
    public function reportsThrowWithoutDeclaration(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/ThrowsExceptionWithoutDeclaration.php'],
            [
                [
                    "Method Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\MissingThrowsRule\\ThrowsExceptionWithoutDeclaration::run() throws checked exception RuntimeException but it's missing from the PHPDoc @throws tag.",
                    11,
                ],
            ],
            'A method that throws a checked exception without @throws must be reported',
        );
    }

    #[Test]
    public function passesWhenThrowIsDeclared(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/ThrowsExceptionWithDeclaration.php'],
            [],
            'A method that declares @throws for the exception it throws must pass',
        );
    }

    #[Test]
    public function passesWhenMethodDoesNotThrow(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/NoThrowMethod.php'],
            [],
            'A method that throws nothing needs no @throws tag',
        );
    }

    #[Test]
    public function passesForOverriddenMethodOfParentClass(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/ParentWithRunMethod.php',
                __DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/OverriddenMethodWithoutDeclaration.php',
            ],
            [],
            'An overridden method must inherit @throws from its parent by default',
        );
    }

    #[Test]
    public function passesForInterfaceImplementationMethod(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/HasRunMethod.php',
                __DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/InterfaceImplementationWithoutDeclaration.php',
            ],
            [],
            'A method implementing an interface must inherit @throws from the interface by default',
        );
    }

    #[Test]
    public function passesWhenSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/SuppressedMissingThrows.php'],
            [],
            'A @phpstan-ignore haspadar.missingThrows comment must silence the report',
        );
    }
}
