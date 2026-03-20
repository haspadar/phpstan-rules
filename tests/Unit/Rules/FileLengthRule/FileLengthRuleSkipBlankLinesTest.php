<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\FileLengthRule;

use Haspadar\PHPStanRules\Rules\FileLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<FileLengthRule> */
final class FileLengthRuleSkipBlankLinesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new FileLengthRule(15, ['skipBlankLines' => true]);
    }

    #[Test]
    public function passesWhenBlankLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongFileWithBlanks.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenCodeLinesExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongFile.php'],
            [
                ['File LongFile.php is 19 lines long. Maximum allowed is 15.', 3],
            ],
        );
    }

    #[Test]
    public function skipsLinesContainingOnlySpaces(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongFileWithSpacedBlanks.php'],
            [],
        );
    }
}
