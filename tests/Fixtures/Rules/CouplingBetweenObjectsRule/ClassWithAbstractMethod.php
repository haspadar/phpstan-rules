<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

abstract class ClassWithAbstractMethod
{
    abstract public function run(TypeA $a, TypeB $b, TypeC $c, TypeD $d, TypeE $e, TypeF $f): void;
}
