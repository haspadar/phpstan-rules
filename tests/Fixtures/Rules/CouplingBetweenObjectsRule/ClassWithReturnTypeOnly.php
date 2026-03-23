<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithReturnTypeOnly
{
    public function build(): TypeA
    {
        return new TypeA();
    }
}
