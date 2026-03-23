<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithParamAndReturnType
{
    public function convert(TypeA $input): TypeB
    {
        return new TypeB();
    }
}
