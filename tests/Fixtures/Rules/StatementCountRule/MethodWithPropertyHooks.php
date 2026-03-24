<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithPropertyHooks
{
    public string $title {
        get {
            $prefix = 'Mr.';
            $suffix = 'Jr.';
            $separator = ' ';
            $full = $prefix . $separator . $suffix;
            return $full;
        }
        set(string $value) {
            $trimmed = trim($value);
            $upper = strtoupper($trimmed);
            $this->title = $upper;
        }
    }

    public function run(): string
    {
        $prefix = 'Hello';
        $separator = ', ';
        $suffix = 'world';
        return $prefix . $separator . $suffix . $this->title;
    }
}
