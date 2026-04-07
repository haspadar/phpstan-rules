<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassLengthRule;

final class LongClassWithPlainBlockComment
{
    /*
    block comment line one
    block comment line two
    */
    public function run(): string
    {
        $a = 'one';
        $b = 'two';
        $c = 'three';
        $d = 'four';
        $e = 'five';
        $f = 'six';
        return $a . $b . $c . $d . $e . $f;
    }
}
