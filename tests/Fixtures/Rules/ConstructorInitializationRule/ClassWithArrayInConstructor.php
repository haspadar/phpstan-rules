<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithArrayInConstructor
{
    /** @var list<string> */
    private array $tags;

    /** @param list<string> $tags */
    public function __construct(array $tags)
    {
        $this->tags = $tags;
    }
}
