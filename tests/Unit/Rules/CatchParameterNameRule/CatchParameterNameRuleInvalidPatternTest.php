<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CatchParameterNameRule;

use Haspadar\PHPStanRules\Rules\CatchParameterNameRule;
use PHPStan\ShouldNotHappenException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/** @covers CatchParameterNameRule */
final class CatchParameterNameRuleInvalidPatternTest extends TestCase
{
    #[Test]
    public function throwsExceptionWhenPatternIsInvalid(): void
    {
        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Invalid catch parameter name pattern "[invalid".');

        new CatchParameterNameRule('[invalid');
    }
}
