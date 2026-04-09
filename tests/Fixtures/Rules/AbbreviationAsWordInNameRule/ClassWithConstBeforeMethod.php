<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AbbreviationAsWordInNameRule;

final class ClassWithConstBeforeMethod
{
    public const string API_KEY = 'key';

    public function parseJSONAPI(): void
    {
    }
}
