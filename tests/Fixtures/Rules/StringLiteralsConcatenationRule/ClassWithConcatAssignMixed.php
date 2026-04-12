<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StringLiteralsConcatenationRule;

final class ClassWithConcatAssignMixed
{
    public function build(): string
    {
        $text = 'start';
        $text .= "done";

        return $text;
    }
}
