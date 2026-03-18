<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\FileLengthRule;

use Haspadar\PHPStanRules\Rules\FileLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Verifies that only comment lines are skipped, not the code lines that follow them
 *
 * @extends RuleTestCase<FileLengthRule>
 */
final class FileLengthRuleSkipCommentsVariantsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new FileLengthRule(13, ['skipComments' => true]);
    }

    #[Test]
    public function countsCodeLineAfterSlashComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongFileWithInlineSlashComment.php'],
            [
                ['File LongFileWithInlineSlashComment.php is 14 lines long. Maximum allowed is 13.', 3],
            ],
        );
    }
}
