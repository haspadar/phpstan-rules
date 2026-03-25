<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MutableExceptionRule;

final class MultipleMutableException extends \RuntimeException
{
    private string $resource;

    private string $context;

    public function __construct(string $resource, string $context)
    {
        $this->resource = $resource;
        $this->context = $context;
        parent::__construct("Not found: {$resource} in {$context}");
    }
}
