<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class ClassWithNullLiteralOutsideCall
{
    public function run(): ?string
    {
        $value = null;

        return $value;
    }
}
