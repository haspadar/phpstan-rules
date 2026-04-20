<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

final class AnonymousDisjointClass
{
    public function make(): object
    {
        return new class {
            private int $a = 0;

            private int $b = 0;

            private int $c = 0;

            public function firstGroupOne(): int
            {
                return $this->a;
            }

            public function firstGroupTwo(): int
            {
                return $this->a + 1;
            }

            public function firstGroupThree(): int
            {
                return $this->a * 2;
            }

            public function secondGroupOne(): int
            {
                return $this->b;
            }

            public function secondGroupTwo(): int
            {
                return $this->b + $this->c;
            }

            public function secondGroupThree(): int
            {
                return $this->c;
            }

            public function secondGroupFour(): int
            {
                return $this->c * 3;
            }
        };
    }
}
