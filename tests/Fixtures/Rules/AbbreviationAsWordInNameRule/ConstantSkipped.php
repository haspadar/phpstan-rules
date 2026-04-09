<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AbbreviationAsWordInNameRule;

final class ConstantSkipped
{
    public const string MAX_HTTP_SIZE = '1024';

    public const string HTTPS_TIMEOUT = '30';

    public function run(): void
    {
    }
}
