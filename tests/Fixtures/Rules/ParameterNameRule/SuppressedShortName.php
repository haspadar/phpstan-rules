<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNameRule;

final class SuppressedShortName
{
    /** @phpstan-ignore haspadar.parameterName */
    public function run(string $fn): void
    {
        echo $fn;
    }
}
