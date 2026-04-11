<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class ByReferenceMethod
{
    /** @var int */
    private int $value = 42;

    public function &run(): int
    {
        $result = &$this->value;
        return $result;
    }
}
