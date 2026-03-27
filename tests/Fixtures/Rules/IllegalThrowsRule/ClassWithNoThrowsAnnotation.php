<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithNoThrowsAnnotation
{
    public function run(int $value): void {}
}
