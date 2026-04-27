<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\SwitchDefaultRule;

final class EmptySwitch
{
    public function run(string $status): void
    {
        switch ($status) {
        }
    }
}
