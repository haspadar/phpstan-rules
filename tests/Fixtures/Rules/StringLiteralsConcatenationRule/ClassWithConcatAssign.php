<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StringLiteralsConcatenationRule;

final class ClassWithConcatAssign
{
    public function build(): string
    {
        $text = '';
        $text .= "done";

        return $text;
    }
}
