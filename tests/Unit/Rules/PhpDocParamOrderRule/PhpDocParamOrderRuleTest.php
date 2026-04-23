<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocParamOrderRule;

use Haspadar\PHPStanRules\Rules\PhpDocParamOrderRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocParamOrderRule> */
final class PhpDocParamOrderRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new PhpDocParamOrderRule(['checkPublicOnly' => false, 'skipOverridden' => false]);
    }

    #[Test]
    public function passesWhenTagsMatchSignatureOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithCorrectOrder.php'],
            [],
            'Tags in the same order as the signature must not produce any error',
        );
    }

    #[Test]
    public function reportsSwappedPairOfTags(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithWrongOrder.php'],
            [
                ['@param order for add() does not match the signature: expected $a, $b, got $b, $a.', 15],
            ],
            'Two @param tags in the opposite order to the signature must be reported once',
        );
    }

    #[Test]
    public function reportsThreeSwappedTags(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithThreeSwapped.php'],
            [
                ['@param order for sum() does not match the signature: expected $a, $b, $c, got $c, $a, $b.', 16],
            ],
            'A larger permutation must be reported once with expected and actual order listed',
        );
    }

    #[Test]
    public function passesWhenSubsetOfTagsAppearsInSignatureOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithSubsetInOrder.php'],
            [],
            'A subset of @param tags in the signature order must not produce any error; missing tags are another rule',
        );
    }

    #[Test]
    public function reportsSubsetOfTagsInWrongOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithSubsetWrongOrder.php'],
            [
                ['@param order for sum() does not match the signature: expected $a, $c, got $c, $a.', 15],
            ],
            'Even a partial set of @param tags must follow the signature order',
        );
    }

    #[Test]
    public function passesWhenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithNoPhpDoc.php'],
            [],
            'Absent PHPDoc is the concern of PhpDocMissingMethodRule, not this rule',
        );
    }

    #[Test]
    public function reportsWrongOrderOnOverrideWhenOptionDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithOverriddenMethod.php'],
            [
                ['@param order for add() does not match the signature: expected $a, $b, got $b, $a.', 31],
            ],
            'When skipOverridden=false, #[Override] methods must also require matching @param order',
        );
    }

    #[Test]
    public function reportsWrongOrderInPrivateMethodWhenOptionDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithPrivateMethod.php'],
            [
                ['@param order for normalise() does not match the signature: expected $first, $second, got $second, $first.', 15],
            ],
            'When checkPublicOnly=false, private methods must also require matching @param order',
        );
    }

    #[Test]
    public function reportsWrongOrderForVariadicParameter(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithVariadicParam.php'],
            [
                ['@param order for concat() does not match the signature: expected $prefix, $parts, got $parts, $prefix.', 15],
            ],
            'Variadic parameters are matched by their bare name and must follow the signature order',
        );
    }

    #[Test]
    public function reportsWrongOrderForPromotedParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithPromotedParameters.php'],
            [
                ['@param order for __construct() does not match the signature: expected $name, $age, got $age, $name.', 15],
            ],
            'Constructor property promotion parameters must be checked like ordinary parameters',
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/SuppressedWrongOrder.php'],
            [],
            'A @phpstan-ignore haspadar.phpdocParamOrder comment must silence the error',
        );
    }
}
