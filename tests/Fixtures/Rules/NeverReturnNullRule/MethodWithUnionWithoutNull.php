<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule;

final class MethodWithUnionWithoutNull
{
    public function greet(): string|int
    {
        return 'hello';
    }
}
