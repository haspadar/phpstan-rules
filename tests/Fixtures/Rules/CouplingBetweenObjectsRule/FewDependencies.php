<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class FewDependencies
{
    public function __construct(
        private readonly TypeA $a,
        private readonly TypeB $b,
    ) {
    }

    public function process(TypeC $c): TypeD
    {
        return new TypeD();
    }
}
