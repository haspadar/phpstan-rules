<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InheritanceDepthRule\SuppressedTooDeep;

/** @phpstan-ignore haspadar.inheritanceDepth */
final class SuppressedTooDeepLeaf extends SuppressedTooDeepLevelThree
{
}
