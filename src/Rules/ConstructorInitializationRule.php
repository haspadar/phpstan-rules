<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Detects logic in class constructors that goes beyond property initialization.
 * A constructor must only contain assignments to $this->property and calls to
 * parent::__construct(). Any other statements — function calls, method calls,
 * conditionals, loops — are reported as errors. Constructor property promotion
 * parameters produce no body statements and are always permitted.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ConstructorInitializationRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        if ($node->name->toString() !== '__construct') {
            return [];
        }

        $errors = [];

        foreach ($node->stmts ?? [] as $stmt) {
            if ($this->isAllowedStatement($stmt)) {
                continue;
            }

            $reflection = $scope->getClassReflection();

            if ($reflection === null) {
                throw new ShouldNotHappenException();
            }

            $className = $reflection->getName();

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Constructor of %s must only initialize properties. Found: %s.',
                    $className,
                    $stmt->getType(),
                ),
            )
                ->identifier('haspadar.constructorInit')
                ->line($stmt->getLine())
                ->build();
        }

        return $errors;
    }

    private function isAllowedStatement(Node\Stmt $stmt): bool
    {
        if (!$stmt instanceof Expression) {
            return false;
        }

        return $this->isThisPropertyAssignment($stmt) || $this->isParentConstructorCall($stmt);
    }

    private function isThisPropertyAssignment(Expression $stmt): bool
    {
        return $stmt->expr instanceof Assign
            && $stmt->expr->var instanceof PropertyFetch
            && $stmt->expr->var->var instanceof Variable
            && $stmt->expr->var->var->name === 'this'
            && $this->isAllowedValue($stmt->expr->expr);
    }

    private function isAllowedValue(Expr $expr): bool
    {
        return $expr instanceof Variable
            || $expr instanceof Expr\New_
            || $expr instanceof Expr\Array_
            || $expr instanceof Expr\ConstFetch
            || $expr instanceof Expr\ClassConstFetch
            || $expr instanceof Node\Scalar\String_
            || $expr instanceof Node\Scalar\Int_
            || $expr instanceof Node\Scalar\Float_;
    }

    private function isParentConstructorCall(Expression $stmt): bool
    {
        return $stmt->expr instanceof StaticCall
            && $stmt->expr->class instanceof Name
            && $stmt->expr->class->toString() === 'parent'
            && $stmt->expr->name instanceof Node\Identifier
            && $stmt->expr->name->toString() === '__construct';
    }
}
