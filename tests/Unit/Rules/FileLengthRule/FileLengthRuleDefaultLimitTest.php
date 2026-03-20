<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\FileLengthRule;

use Haspadar\PHPStanRules\Rules\FileLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<FileLengthRule> */
final class FileLengthRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new FileLengthRule();
    }

    #[Test]
    public function passesWhenFileIsExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/ExactDefaultFile.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenFileExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/FileLengthRule/LongDefaultFile.php'],
            [
                ['File LongDefaultFile.php is 1001 lines long. Maximum allowed is 1000.', 3],
            ],
        );
    }
}
