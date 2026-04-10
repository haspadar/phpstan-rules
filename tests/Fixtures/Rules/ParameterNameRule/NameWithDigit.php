<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNameRule;

final class NameWithDigit
{
    public function run(int $item2): void
    {
        echo $item2;
    }
}
