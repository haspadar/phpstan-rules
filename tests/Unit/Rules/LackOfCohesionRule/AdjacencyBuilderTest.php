<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule\AdjacencyBuilder;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Exercises AdjacencyBuilder directly for input sizes that the rule filter never reaches.
 */
final class AdjacencyBuilderTest extends TestCase
{
    #[Test]
    public function returnsEmptyAdjacencyForEmptyMethodList(): void
    {
        self::assertSame(
            [],
            (new AdjacencyBuilder())->build([], []),
            'zero methods must produce an empty adjacency map',
        );
    }

    #[Test]
    public function buildsAdjacencyWithoutPropertyEdgesForSingleMethod(): void
    {
        $method = new ClassMethod(new Identifier('only'));

        self::assertSame(
            [0 => []],
            (new AdjacencyBuilder())->build([$method], [['properties' => ['a'], 'calls' => []]]),
            'a single method has no pairs to compare and yields an isolated node',
        );
    }
}
