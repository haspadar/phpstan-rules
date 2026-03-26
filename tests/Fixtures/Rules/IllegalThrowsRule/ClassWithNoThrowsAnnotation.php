<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\IllegalThrowsRule;

final class ClassWithNoThrowsAnnotation
{
    /**
     * @param int $value some value
     *
     * @return void
     */
    public function run(int $value): void {}
}
