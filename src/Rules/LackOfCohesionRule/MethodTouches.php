<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\LackOfCohesionRule;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;

/**
 * Collects property accesses and method calls performed by a class method.
 *
 * Both `$this->x` instance fetches and `self::$x` / `static::$x` static fetches are
 * treated as references to the same "property" for cohesion analysis. Method names are
 * normalised to lowercase because PHP method names are case-insensitive.
 */
final readonly class MethodTouches
{
    /**
     * Returns property names and called method names referenced from the method body.
     *
     * Method names are lowercased; property names keep their original case (PHP property
     * names are case-sensitive).
     *
     * @param ClassMethod $method Method whose body is scanned for property and call references.
     * @return array{properties: list<string>, calls: list<string>}
     */
    public function collect(ClassMethod $method): array
    {
        $finder = new NodeFinder();
        $statements = array_values($method->stmts ?? []);

        return [
            'properties' => $this->properties($finder, $statements),
            'calls' => $this->calls($finder, $statements),
        ];
    }

    /**
     * Returns the names of `$this->x`, `self::$x` and `static::$x` references.
     *
     * @param list<Node> $statements
     * @return list<string>
     */
    private function properties(NodeFinder $finder, array $statements): array
    {
        $names = [];

        foreach ($finder->find(
            $statements,
            static fn(Node $inner): bool => $inner instanceof PropertyFetch,
        ) as $fetch) {
            assert($fetch instanceof PropertyFetch);
            $name = $this->instancePropertyName($fetch);

            if ($name !== null) {
                $names[] = $name;
            }
        }

        foreach ($finder->find(
            $statements,
            static fn(Node $inner): bool => $inner instanceof StaticPropertyFetch,
        ) as $fetch) {
            assert($fetch instanceof StaticPropertyFetch);
            $name = $this->staticPropertyName($fetch);

            if ($name !== null) {
                $names[] = $name;
            }
        }

        return array_values(array_unique($names));
    }

    /**
     * Returns the lowercased names of methods called via `$this->method()`, `self::method()` or `static::method()`.
     *
     * @param list<Node> $statements
     * @return list<string>
     */
    private function calls(NodeFinder $finder, array $statements): array
    {
        $names = [];

        foreach ($finder->find(
            $statements,
            static fn(Node $inner): bool => $inner instanceof MethodCall,
        ) as $call) {
            assert($call instanceof MethodCall);
            $name = $this->instanceCallName($call);

            if ($name !== null) {
                $names[] = $name;
            }
        }

        foreach ($finder->find(
            $statements,
            static fn(Node $inner): bool => $inner instanceof StaticCall,
        ) as $call) {
            assert($call instanceof StaticCall);
            $name = $this->staticCallName($call);

            if ($name !== null) {
                $names[] = $name;
            }
        }

        return array_values(array_unique($names));
    }

    /**
     * Returns the property name if the fetch is `$this->x`, otherwise null.
     */
    private function instancePropertyName(PropertyFetch $fetch): ?string
    {
        if ($fetch->var instanceof Variable && $fetch->var->name === 'this' && $fetch->name instanceof Identifier) {
            return $fetch->name->toString();
        }

        return null;
    }

    /**
     * Returns the property name if the fetch is `self::$x` / `static::$x`, otherwise null.
     */
    private function staticPropertyName(StaticPropertyFetch $fetch): ?string
    {
        if ($fetch->class instanceof Name
            && in_array($fetch->class->toLowerString(), ['self', 'static'], true)
            && $fetch->name instanceof Identifier
        ) {
            return $fetch->name->toString();
        }

        return null;
    }

    /**
     * Returns the lowercased method name if the call is `$this->method()`, otherwise null.
     */
    private function instanceCallName(MethodCall $call): ?string
    {
        if ($call->var instanceof Variable && $call->var->name === 'this' && $call->name instanceof Identifier) {
            return strtolower($call->name->toString());
        }

        return null;
    }

    /**
     * Returns the lowercased method name if the call is `self::method()` / `static::method()`, otherwise null.
     */
    private function staticCallName(StaticCall $call): ?string
    {
        if ($call->class instanceof Name
            && in_array($call->class->toLowerString(), ['self', 'static'], true)
            && $call->name instanceof Identifier
        ) {
            return strtolower($call->name->toString());
        }

        return null;
    }
}
