<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParamDescriptionCapitalRule;

final class MethodWithNoPhpDoc
{
    public function getName(string $name): string
    {
        return $name;
    }
}
