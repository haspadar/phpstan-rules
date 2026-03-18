<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLinesRule;

use Haspadar\PHPStanRules\Rules\MethodLinesRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLinesRule> */
final class MethodLinesRuleSkipCommentsVariantsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLinesRule(8, ['skipComments' => true]);
    }

    #[Test]
    public function reportsErrorWhenSlashCommentIsInline(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/InlineSlashCommentsMethod.php'],
            [
                ['Method run() is 9 lines long. Maximum allowed is 8.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenHashCommentIsInline(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/InlineHashCommentsMethod.php'],
            [
                ['Method run() is 9 lines long. Maximum allowed is 8.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenPhpDocCommentIsInline(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/InlinePhpDocCommentsMethod.php'],
            [
                ['Method run() is 9 lines long. Maximum allowed is 8.', 9],
            ],
        );
    }

    #[Test]
    public function passesWhenSlashCommentOccupiesWholeLine(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/SlashCommentLinesMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenBlockCommentOccupiesWholeLine(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLinesRule/BlockCommentLinesMethod.php'],
            [],
        );
    }
}
