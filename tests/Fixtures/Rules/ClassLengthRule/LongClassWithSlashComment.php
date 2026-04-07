<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassLengthRule;

final class LongClassWithSlashComment
{
    // helper method
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
