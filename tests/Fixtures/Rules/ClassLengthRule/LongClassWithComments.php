<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassLengthRule;

final class LongClassWithComments
{
    // loads brand
    // loads model
    public function run(): string
    {
        $a = 'one';
        $b = 'two';
        return $a . $b;
    }
}
