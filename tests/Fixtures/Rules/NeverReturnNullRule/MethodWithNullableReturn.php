<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule;

final class MethodWithNullableReturn
{
    public function greet(): ?string
    {
        return 'hello';
    }
}
