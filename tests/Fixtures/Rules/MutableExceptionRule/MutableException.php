<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MutableExceptionRule;

final class MutableException extends \RuntimeException
{
    private string $resource;

    public function __construct(string $resource)
    {
        $this->resource = $resource;
        parent::__construct("Not found: {$resource}");
    }
}
