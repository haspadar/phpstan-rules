<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNameRule;

final class MultipleParameters
{
    public function run(string $x, int $value, bool $ok): void
    {
        echo $x . $value . $ok;
    }
}
