<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class ClassWithExcludedDependencies
{
    public function __construct(
        private readonly TypeA $a,
        private readonly TypeB $b,
        private readonly TypeC $c,
        private readonly TypeD $d,
        private readonly TypeE $e,
        private readonly TypeF $f,
    ) {
    }

    public function run(TypeG $g): TypeH
    {
        $i = new TypeI();
        TypeJ::create();

        return new TypeH();
    }
}
