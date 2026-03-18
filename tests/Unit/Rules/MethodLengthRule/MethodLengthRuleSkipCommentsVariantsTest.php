<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLengthRule;

use Haspadar\PHPStanRules\Rules\MethodLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLengthRule> */
final class MethodLengthRuleSkipCommentsVariantsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLengthRule(8, ['skipComments' => true]);
    }

    #[Test]
    public function reportsErrorWhenSlashCommentIsInline(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/InlineSlashCommentsMethod.php'],
            [
                ['Method run() is 9 lines long. Maximum allowed is 8.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenHashCommentIsInline(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/InlineHashCommentsMethod.php'],
            [
                ['Method run() is 9 lines long. Maximum allowed is 8.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenPhpDocCommentIsInline(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/InlinePhpDocCommentsMethod.php'],
            [
                ['Method run() is 9 lines long. Maximum allowed is 8.', 9],
            ],
        );
    }

    #[Test]
    public function passesWhenSlashCommentOccupiesWholeLine(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/SlashCommentLinesMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenBlockCommentOccupiesWholeLine(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/BlockCommentLinesMethod.php'],
            [],
        );
    }
}
