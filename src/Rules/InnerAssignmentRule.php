<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\While_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects assignments used as subexpressions rather than standalone statements.
 * Reports any Assign, AssignOp, or AssignRef node that is not a direct child of
 * an Expression statement. Loop idioms in while/do-while/for conditions are
 * excluded because the pattern is conventional and unambiguous.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class InnerAssignmentRule implements Rule
{
    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     *
     * @throws \PHPStan\ShouldNotHappenException
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        $loopCondAssigns = $this->collectLoopConditionAssigns($node);

        $errors = [];

        /** @var list<Assign|AssignOp|AssignRef> $assigns */
        $assigns = (new NodeFinder())->find(
            $node->stmts ?? [],
            static fn(Node $n): bool => $n instanceof Assign
                || $n instanceof AssignOp
                || $n instanceof AssignRef,
        );

        foreach ($assigns as $assign) {
            if ($this->isStandaloneStatement($assign, $node)) {
                continue;
            }

            if (in_array($assign, $loopCondAssigns, true)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                'Inner assignment found. Assignments must not be used as subexpressions.',
            )
                ->identifier('haspadar.innerAssignment')
                ->line($assign->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if the assignment node is the direct expression of any
     * standalone Expression statement anywhere within the method body (i.e. it
     * is used as a statement, not nested inside another expression).
     *
     * @param Assign|AssignOp|AssignRef $assign
     * @param ClassMethod $method
     */
    private function isStandaloneStatement(Assign|AssignOp|AssignRef $assign, ClassMethod $method): bool
    {
        /** @var list<Expression> $expressions */
        $expressions = (new NodeFinder())->findInstanceOf($method->stmts ?? [], Expression::class);

        foreach ($expressions as $stmt) {
            if ($stmt->expr === $assign) {
                return true;
            }
        }

        return false;
    }

    /**
     * Collects all Assign/AssignOp/AssignRef nodes that appear in loop
     * conditions (while, do-while, for), which are conventional idioms
     * and are excluded from the rule.
     *
     * @param ClassMethod $method
     *
     * @return list<Assign|AssignOp|AssignRef>
     */
    private function collectLoopConditionAssigns(ClassMethod $method): array
    {
        $result = [];

        /** @var list<While_|Do_|For_> $loops */
        $loops = (new NodeFinder())->findInstanceOf($method->stmts ?? [], While_::class);
        $loops = array_merge($loops, (new NodeFinder())->findInstanceOf($method->stmts ?? [], Do_::class));
        $loops = array_merge($loops, (new NodeFinder())->findInstanceOf($method->stmts ?? [], For_::class));

        foreach ($loops as $loop) {
            $condNodes = match (true) {
                $loop instanceof While_ => [$loop->cond],
                $loop instanceof Do_ => [$loop->cond],
                $loop instanceof For_ => $loop->cond,
            };

            foreach ($condNodes as $cond) {
                /** @var list<Assign|AssignOp|AssignRef> $found */
                $found = (new NodeFinder())->find(
                    [$cond],
                    static fn(Node $n): bool => $n instanceof Assign
                        || $n instanceof AssignOp
                        || $n instanceof AssignRef,
                );

                $result = array_merge($result, $found);
            }
        }

        return $result;
    }
}
