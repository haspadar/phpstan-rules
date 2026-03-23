<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule;

$obj = new class (new TypeA(), new TypeB()) {
    public function __construct(private TypeA $a, private TypeB $b)
    {
    }
};
