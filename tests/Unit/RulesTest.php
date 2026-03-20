<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit;

use Haspadar\PHPStanRules\Rules;
use Haspadar\PHPStanRules\Rules\CyclomaticComplexityRule;
use Haspadar\PHPStanRules\Rules\FileLengthRule;
use Haspadar\PHPStanRules\Rules\MethodLengthRule;
use Haspadar\PHPStanRules\Rules\ParameterNumberRule;
use Haspadar\PHPStanRules\Rules\TooManyMethodsRule;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RulesTest extends TestCase
{
    #[Test]
    public function returnsAllRegisteredRules(): void
    {
        self::assertSame(
            [MethodLengthRule::class, FileLengthRule::class, TooManyMethodsRule::class, ParameterNumberRule::class, CyclomaticComplexityRule::class],
            (new Rules())->all(),
            'Rules::all() must list every registered rule class',
        );
    }
}
