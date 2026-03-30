<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocMissingPropertyRule;

use Haspadar\PHPStanRules\Rules\PhpDocMissingPropertyRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocMissingPropertyRule> */
final class PhpDocMissingPropertyRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocMissingPropertyRule();
    }

    #[Test]
    public function passesWhenPublicPropertyHasPhpDocWithDefaultOptions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingPropertyRule/ClassWithPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenPublicPropertyMissingPhpDocWithDefaultOptions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingPropertyRule/ClassWithMissingPhpDoc.php'],
            [
                ['PHPDoc is missing for property $name.', 9],
            ],
        );
    }
}
