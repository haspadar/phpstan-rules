<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AbbreviationAsWordInNameRule;

final class ClassWithMultipleViolations
{
    private string $HTTPSConnection;

    public function parseJSONAPI(string $XMLHTTPRequest): void
    {
    }
}
