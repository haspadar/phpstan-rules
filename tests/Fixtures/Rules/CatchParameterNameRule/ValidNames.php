<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CatchParameterNameRule;

final class ValidNames
{
    public function run(): void
    {
        try {
            echo 'ok';
        } catch (\RuntimeException $e) {
            echo $e->getMessage();
        } catch (\LogicException $ex) {
            echo $ex->getMessage();
        } catch (\Throwable $error) {
            echo $error->getMessage();
        }
    }
}
