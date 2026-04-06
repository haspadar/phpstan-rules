<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoLineCommentBeforeDeclarationRule;

use Haspadar\PHPStanRules\Rules\NoLineCommentBeforeDeclarationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoLineCommentBeforeDeclarationRule> */
final class NoLineCommentBeforeDeclarationRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoLineCommentBeforeDeclarationRule();
    }

    #[Test]
    public function passesWhenNoComments(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/NoComments.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenPhpDocComments(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/PhpDocComments.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenBlockComments(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/BlockComments.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenLineCommentBeforeClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/LineCommentBeforeClass.php'],
            [
                [
                    'Class LineCommentBeforeClass has a line comment before its declaration; use a PHPDoc block instead.',
                    7,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenLineCommentBeforeMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/LineCommentBeforeMethod.php'],
            [
                [
                    'Method foo() has a line comment before its declaration; use a PHPDoc block instead.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenHashCommentBeforeProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/HashCommentBeforeProperty.php'],
            [
                [
                    'Property $name has a line comment before its declaration; use a PHPDoc block instead.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function reportsOnlyLineCommentInMixedComments(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/MixedComments.php'],
            [
                [
                    'Method foo() has a line comment before its declaration; use a PHPDoc block instead.',
                    10,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/SuppressedClass.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenAnonymousClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/AnonymousClass.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenLineCommentBeforeClassConstant(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/ClassConstant.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenLineCommentBeforeTrait(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoLineCommentBeforeDeclarationRule/LineCommentBeforeTrait.php'],
            [
                [
                    'Trait LineCommentBeforeTrait has a line comment before its declaration; use a PHPDoc block instead.',
                    7,
                ],
            ],
        );
    }
}
