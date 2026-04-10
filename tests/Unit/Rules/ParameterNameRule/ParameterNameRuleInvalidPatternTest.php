<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ParameterNameRule;

use Haspadar\PHPStanRules\Rules\ParameterNameRule;
use PHPStan\ShouldNotHappenException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/** @covers ParameterNameRule */
final class ParameterNameRuleInvalidPatternTest extends TestCase
{
    #[Test]
    public function throwsExceptionWhenPatternIsInvalid(): void
    {
        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage('Invalid parameter name pattern "[invalid".');

        new ParameterNameRule('[invalid');
    }
}
