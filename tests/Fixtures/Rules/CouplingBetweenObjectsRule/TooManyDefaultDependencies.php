<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

final class TooManyDefaultDependencies
{
    public function __construct(
        private readonly TypeA $a,
        private readonly TypeB $b,
        private readonly TypeC $c,
        private readonly TypeD $d,
        private readonly TypeE $e,
        private readonly TypeF $f,
        private readonly TypeG $g,
        private readonly TypeH $h,
        private readonly TypeI $i,
        private readonly TypeJ $j,
        private readonly TypeK $k,
        private readonly TypeL $l,
        private readonly TypeM $m,
        private readonly TypeN $n,
        private readonly TypeO $o,
        private readonly TypeP $p,
    ) {
    }
}
