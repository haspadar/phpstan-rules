<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit;

use Haspadar\PHPStanRules\Rules;
use Haspadar\PHPStanRules\Rules\FileLengthRule;
use Haspadar\PHPStanRules\Rules\TooManyMethodsRule;
use Haspadar\PHPStanRules\Rules\MethodLengthRule;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RulesTest extends TestCase
{
    #[Test]
    public function returnsAllRegisteredRules(): void
    {
        self::assertSame(
            [MethodLengthRule::class, FileLengthRule::class, TooManyMethodsRule::class],
            (new Rules())->all(),
            'Rules::all() must list every registered rule class',
        );
    }
}
