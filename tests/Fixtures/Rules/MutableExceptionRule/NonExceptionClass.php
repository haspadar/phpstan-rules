<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MutableExceptionRule;

final class NonExceptionClass
{
    private string $resource;

    public function __construct(string $resource)
    {
        $this->resource = $resource;
    }
}
