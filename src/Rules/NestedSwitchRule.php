<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\NestedSwitchRule\NestedSwitchVisitor;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\ShouldNotHappenException;

/**
 * Reports switch statements nested inside another switch statement.
 *
 * A switch inside another switch hides flow of control behind two levels of
 * branching and almost always signals that the enclosing method is doing too
 * much. Extract the inner switch into its own method instead.
 *
 * Mirrors Checkstyle's NestedIfDepth, NestedForDepth and NestedTryDepth modules.
 * Closure and arrow-function bodies reset the scope — a switch inside a nested
 * closure is not considered nested relative to the outer switch.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class NestedSwitchRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the method and returns errors for every nested switch.
     *
     * @psalm-param ClassMethod $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->stmts === null) {
            return [];
        }

        $visitor = new NestedSwitchVisitor();
        $traverser = new NodeTraverser($visitor);
        $traverser->traverse($node->stmts);

        return $visitor->errors();
    }
}
