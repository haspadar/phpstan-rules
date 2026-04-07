<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\NodeHelper\ChildNodes;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Detects modifications of loop control variables inside the loop body.
 * For a `for` loop, the control variables are those declared in the init
 * expressions. For a `foreach` loop, the iteration variable (and key
 * variable if present) are considered control variables. Any assignment,
 * compound assignment, increment, or decrement of those variables inside
 * the loop body is reported.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ModifiedControlVariableRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-suppress RedundantFunctionCall -- array<Stmt> per PHPStan vs list<Stmt> per Psalm; array_values() needed for PHPStan
     * @psalm-param ClassMethod $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        /** @var list<For_> $forLoops */
        $forLoops = (new NodeFinder())->findInstanceOf($node->stmts ?? [], For_::class);

        foreach ($forLoops as $loop) {
            $controlVars = $this->collectForControlVars($loop);
            array_push($errors, ...$this->findModificationsInBody(array_values($loop->stmts), $controlVars, 'for'));
        }

        /** @var list<Foreach_> $foreachLoops */
        $foreachLoops = (new NodeFinder())->findInstanceOf($node->stmts ?? [], Foreach_::class);

        foreach ($foreachLoops as $loop) {
            $controlVars = $this->collectForeachControlVars($loop);
            array_push($errors, ...$this->findModificationsInBody(array_values($loop->stmts), $controlVars, 'foreach'));
        }

        return $errors;
    }

    /**
     * Collects variable names declared in the init expressions of a `for` loop.
     *
     * @return list<string>
     */
    private function collectForControlVars(For_ $loop): array
    {
        $vars = [];

        foreach ($loop->init as $initExpr) {
            /** @var list<Assign> $assigns */
            $assigns = (new NodeFinder())->findInstanceOf([$initExpr], Assign::class);

            foreach ($assigns as $assign) {
                if ($assign->var instanceof Variable && is_string($assign->var->name)) {
                    $vars[] = $assign->var->name;
                }
            }
        }

        return $vars;
    }

    /**
     * Collects variable names used as iteration variables in a `foreach` loop.
     *
     * @return list<string>
     */
    private function collectForeachControlVars(Foreach_ $loop): array
    {
        $vars = [];

        if ($loop->valueVar instanceof Variable && is_string($loop->valueVar->name)) {
            $vars[] = $loop->valueVar->name;
        }

        if ($loop->keyVar instanceof Variable && is_string($loop->keyVar->name)) {
            $vars[] = $loop->keyVar->name;
        }

        return $vars;
    }

    /**
     * Finds all modifications of the given control variable names within the loop body statements.
     *
     * @param list<Node\Stmt> $stmts
     * @param list<string> $controlVars
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function findModificationsInBody(
        array $stmts,
        array $controlVars,
        string $loopType,
    ): array {
        if ($controlVars === []) {
            return [];
        }

        $errors = [];

        /** @var list<Assign|AssignOp|AssignRef|PreInc|PostInc|PreDec|PostDec> $modifications */
        $modifications = $this->collectModificationsSkippingNestedScopes($stmts);

        foreach ($modifications as $mod) {
            $varNode = $this->extractVarNode($mod);

            if (!($varNode instanceof Variable) || !is_string($varNode->name)) {
                continue;
            }

            if (!in_array($varNode->name, $controlVars, true)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    '%s loop control variable $%s must not be modified inside the loop body.',
                    ucfirst($loopType),
                    $varNode->name,
                ),
            )
                ->identifier('haspadar.modifiedControlVar')
                ->line($mod->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Collects modification nodes from statements without descending into nested scopes.
     *
     * @param list<Node> $nodes
     * @return list<Node>
     */
    private function collectModificationsSkippingNestedScopes(array $nodes): array
    {
        $result = [];

        foreach ($nodes as $node) {
            if ($node instanceof Closure || $node instanceof ArrowFunction) {
                continue;
            }

            if ($this->isModificationNode($node)) {
                $result[] = $node;
            }

            array_push($result, ...$this->collectModificationsSkippingNestedScopes(ChildNodes::of($node)));
        }

        return $result;
    }

    /**
     * Returns true if the node is any kind of variable modification expression.
     */
    private function isModificationNode(Node $node): bool
    {
        return $node instanceof Assign
            || $node instanceof AssignOp
            || $node instanceof AssignRef
            || $node instanceof PreInc
            || $node instanceof PostInc
            || $node instanceof PreDec
            || $node instanceof PostDec;
    }

    /**
     * Extracts the variable node from any modification expression.
     */
    private function extractVarNode(
        Assign|AssignOp|AssignRef|PreInc|PostInc|PreDec|PostDec $mod,
    ): Node\Expr {
        return $mod->var;
    }
}
