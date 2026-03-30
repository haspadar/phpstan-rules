<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnDescriptionCapitalRule;

final class MethodWithNoPhpDoc
{
    public function getName(): string
    {
        return '';
    }
}
