<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassLengthRule;

final class LongClassWithBlanksNoSkip
{

    public function run(): string
    {

        $a = 'one';

        $b = 'two';
        return $a . $b;
    }
}
