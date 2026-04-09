<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AbbreviationAsWordInNameRule;

final class CleanClass
{
    private string $httpClient;

    public function parseJson(string $xmlData): string
    {
        return $xmlData;
    }
}
