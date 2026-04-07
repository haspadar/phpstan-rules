<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassLengthRule;

final class ClassWithPlainBlockComment
{
    /*
    This class processes data
    and returns results
    based on input values
    */
    public function run(): string
    {
        $a = 'one';
        return $a;
    }
}
