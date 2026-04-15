<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule;

final class MethodWithReturnNull
{
    /** @return mixed */
    public function greet()
    {
        return null;
    }
}
