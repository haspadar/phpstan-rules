<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CatchParameterNameRule;

final class ShortName
{
    public function run(): void
    {
        try {
            echo 'ok';
        } catch (\Throwable $x) {
            echo $x->getMessage();
        }
    }
}
