<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\FileLengthRule;

use Haspadar\PHPStanRules\Rules\FileLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<FileLengthRule> */
final class FileLengthRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new FileLengthRule(15);
    }

    #[Test]
    public function passesWhenFileFitsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/ShortFile.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenFileExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongFile.php'],
            [
                ['File LongFile.php is 23 lines long. Maximum allowed is 15.', 3],
            ],
        );
    }

    #[Test]
    public function passesWhenFileIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/ExactFile.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/SuppressedLongFile.php'],
            [],
        );
    }

    #[Test]
    public function countsCommentLinesWhenSkipCommentsNotEnabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongFileWithComments.php'],
            [
                ['File LongFileWithComments.php is 19 lines long. Maximum allowed is 15.', 3],
            ],
        );
    }
}
