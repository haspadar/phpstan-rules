<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CatchParameterNameRule;

final class MultipleCatchTypes
{
    public function run(): void
    {
        try {
            echo 'ok';
        } catch (\RuntimeException | \LogicException $x) {
            echo $x->getMessage();
        }
    }
}
