<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AbbreviationAsWordInNameRule;

final class ClassWithMultipleProperties
{
    private string $HTTPSFirst;

    private string $HTTPSSecond;

    public function run(): void
    {
    }
}
