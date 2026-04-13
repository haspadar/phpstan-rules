<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ForbiddenClassSuffixRule;

use Haspadar\PHPStanRules\Rules\ForbiddenClassSuffixRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ForbiddenClassSuffixRule> */
final class ForbiddenClassSuffixRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ForbiddenClassSuffixRule();
    }

    #[Test]
    public function reportsErrorForManagerSuffix(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ForbiddenClassSuffixRule/ClassWithManagerSuffix.php'],
            [
                [
                    "Class UserManager uses forbidden suffix 'Manager'. Rename to describe its responsibility.",
                    7,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForHelperSuffix(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ForbiddenClassSuffixRule/ClassWithHelperSuffix.php'],
            [
                [
                    "Class StringHelper uses forbidden suffix 'Helper'. Rename to describe its responsibility.",
                    7,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForDataSuffix(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ForbiddenClassSuffixRule/ClassWithDataSuffix.php'],
            [
                [
                    "Class OrderData uses forbidden suffix 'Data'. Rename to describe its responsibility.",
                    7,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForProcessorSuffix(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ForbiddenClassSuffixRule/ClassWithProcessorSuffix.php'],
            [
                [
                    "Class DataProcessor uses forbidden suffix 'Processor'. Rename to describe its responsibility.",
                    7,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenClassHasCleanName(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ForbiddenClassSuffixRule/ClassWithCleanName.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenClassIsAbstract(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ForbiddenClassSuffixRule/AbstractClassWithSuffix.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ForbiddenClassSuffixRule/SuppressedClass.php'],
            [],
        );
    }
}
