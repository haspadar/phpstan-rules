<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocMissingPropertyRule;

use Haspadar\PHPStanRules\Rules\PhpDocMissingPropertyRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocMissingPropertyRule> */
final class PhpDocMissingPropertyRuleOptionTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocMissingPropertyRule(['checkPublicOnly' => false]);
    }

    #[Test]
    public function reportsErrorWhenPrivatePropertyMissingPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingPropertyRule/ClassWithAllProperties.php'],
            [
                ['PHPDoc is missing for property $name.', 9],
            ],
        );
    }
}
