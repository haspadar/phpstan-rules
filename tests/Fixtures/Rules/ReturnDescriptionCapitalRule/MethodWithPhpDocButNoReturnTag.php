<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnDescriptionCapitalRule;

final class MethodWithPhpDocButNoReturnTag
{
    /**
     * Returns something based on the name.
     *
     * @param string $name The input name
     */
    public function getName(string $name): string
    {
        return $name;
    }
}
