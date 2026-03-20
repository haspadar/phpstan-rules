<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\FileLengthRule;

use Haspadar\PHPStanRules\Rules\FileLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<FileLengthRule> */
final class FileLengthRuleSkipCommentsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new FileLengthRule(15, ['skipComments' => true]);
    }

    #[Test]
    public function passesWhenCommentLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongFileWithComments.php'],
            [],
        );
    }

    #[Test]
    public function skipsPlainBlockCommentBodyLines(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongFileWithPlainBlockComments.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenCodeLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongFile.php'],
            [
                ['File LongFile.php is 23 lines long. Maximum allowed is 15.', 3],
            ],
        );
    }

}
