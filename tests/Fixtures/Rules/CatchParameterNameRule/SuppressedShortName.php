<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CatchParameterNameRule;

final class SuppressedShortName
{
    public function run(): void
    {
        try {
            echo 'ok';
        } catch (\Throwable $x) { /** @phpstan-ignore haspadar.catchParamName */
            echo $x->getMessage();
        }
    }
}
