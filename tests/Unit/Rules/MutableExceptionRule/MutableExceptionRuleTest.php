<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MutableExceptionRule;

use Haspadar\PHPStanRules\Rules\MutableExceptionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MutableExceptionRule> */
final class MutableExceptionRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MutableExceptionRule($this->createReflectionProvider());
    }

    #[Test]
    public function passesWhenExceptionPropertiesAreReadonly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MutableExceptionRule/ReadonlyException.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenExceptionPropertyIsNotReadonly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MutableExceptionRule/MutableException.php'],
            [
                [
                    'Exception property $resource must be readonly to prevent mutation after construction.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenClassIsNotAnException(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MutableExceptionRule/NonExceptionClass.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenExceptionIsAbstract(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MutableExceptionRule/AbstractException.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenPropertyIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MutableExceptionRule/SuppressedException.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForEachMutablePropertyWhenMultipleExist(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MutableExceptionRule/MultipleMutableException.php'],
            [
                [
                    'Exception property $resource must be readonly to prevent mutation after construction.',
                    9,
                ],
                [
                    'Exception property $context must be readonly to prevent mutation after construction.',
                    11,
                ],
            ],
        );
    }
}
