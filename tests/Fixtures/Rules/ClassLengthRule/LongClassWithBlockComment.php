<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassLengthRule;

final class LongClassWithBlockComment
{
    /*
     * This is a block comment
     */
    public function run(): string
    {
        $a = 'one';
        return $a;
    }
}
