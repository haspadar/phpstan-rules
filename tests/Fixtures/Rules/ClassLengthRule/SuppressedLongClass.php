<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassLengthRule;

/** @phpstan-ignore haspadar.classLength */
final class SuppressedLongClass
{
    public function run(): string
    {
        $a = 'one';
        $b = 'two';
        $c = 'three';
        $d = 'four';
        $e = 'five';
        return $a . $b . $c . $d . $e;
    }
}
