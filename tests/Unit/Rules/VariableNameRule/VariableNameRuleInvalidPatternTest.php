<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\VariableNameRule;

use Haspadar\PHPStanRules\Rules\VariableNameRule;
use PHPStan\ShouldNotHappenException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/** @covers VariableNameRule */
final class VariableNameRuleInvalidPatternTest extends TestCase
{
    #[Test]
    public function throwsExceptionWhenPatternIsInvalid(): void
    {
        $this->expectException(ShouldNotHappenException::class);

        new VariableNameRule('[invalid');
    }
}
