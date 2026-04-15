<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule;

final class MethodWithUnionNullReturn
{
    public function greet(): string|null
    {
        return 'hello';
    }
}
