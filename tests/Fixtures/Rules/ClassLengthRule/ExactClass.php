<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassLengthRule;

final class ExactClass
{
    public function run(): string
    {
        $a = 'one';
        $b = 'two';
        $c = 'three';
        return $a . $b . $c;
    }
}
