<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\FinalClassRule;

use Haspadar\PHPStanRules\Rules\FinalClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<FinalClassRule> */
final class FinalClassRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new FinalClassRule();
    }

    #[Test]
    public function passesWhenClassIsFinal(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FinalClassRule/FinalClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassIsNotFinal(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FinalClassRule/NonFinalClass.php'],
            [
                [
                    'Class NonFinalClass must be declared as final.',
                    7,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenClassIsAbstract(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FinalClassRule/AbstractClass.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenClassIsAnonymous(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FinalClassRule/AnonymousClass.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenClassIsNotFinalButSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FinalClassRule/SuppressedClass.php'],
            [],
        );
    }
}
