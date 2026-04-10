<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CatchParameterNameRule;

final class CamelCaseName
{
    public function run(): void
    {
        try {
            echo 'ok';
        } catch (\RuntimeException $myException) {
            echo $myException->getMessage();
        }
    }
}
