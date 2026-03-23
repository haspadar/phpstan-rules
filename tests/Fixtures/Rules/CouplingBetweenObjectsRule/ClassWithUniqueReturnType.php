<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithUniqueReturnType
{
    public function convert(TypeA $input): TypeB
    {
        return $input->toTypeB();
    }
}
