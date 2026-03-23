<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithTwoProperties
{
    private TypeA $a;

    private TypeB $b;
}
