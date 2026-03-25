<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MutableExceptionRule;

final class ReadonlyException extends \RuntimeException
{
    public function __construct(
        private readonly string $resource,
    ) {
        parent::__construct("Not found: {$resource}");
    }
}
