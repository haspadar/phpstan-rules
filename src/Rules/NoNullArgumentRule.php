<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\Internal\BuiltinCallDetector;
use Override;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports the `null` literal passed as an argument to user-defined function, method, or constructor calls.
 * Follows psalm-eo-rules `NoNullChecker`: absence must be modelled explicitly through a Null Object,
 * Optional, or a sensible default value. Internal PHP functions and methods are skipped via PHPStan
 * reflection so that standard-library idioms (for example `str_replace` with null subject) keep working.
 * Dynamic calls where the target cannot be resolved are skipped as well.
 *
 * @implements Rule<CallLike>
 */
final readonly class NoNullArgumentRule implements Rule
{
    /**
     * Accepts the built-in call detector used to skip PHP-native targets.
     *
     * @param BuiltinCallDetector $builtinCallDetector Helper that recognises built-in call targets
     */
    public function __construct(private BuiltinCallDetector $builtinCallDetector) {}

    #[Override]
    public function getNodeType(): string
    {
        return CallLike::class;
    }

    /**
     * Returns one error per null literal passed as an argument.
     *
     * @param CallLike $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($this->isNullBranchOfNullsafeCall($node, $scope)) {
            return [];
        }

        if ($this->builtinCallDetector->isBuiltin($node, $scope)) {
            return [];
        }

        $label = $this->callLabel($node, $scope);
        $errors = [];

        foreach (array_values($node->getArgs()) as $index => $arg) {
            if (!$this->isNullLiteral($arg)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Passing null as argument %s to %s is prohibited. Model absence explicitly (Null Object, Optional).',
                    $this->argumentLabel($arg, $index),
                    $label,
                ),
            )
                ->identifier('haspadar.noNullArgument')
                ->line($arg->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true when the argument value is the `null` constant.
     */
    private function isNullLiteral(Arg $arg): bool
    {
        return $arg->value instanceof ConstFetch
            && $arg->value->name->toLowerString() === 'null';
    }

    /**
     * Produces a quoted name for a named argument or a 1-based `#<position>` token for a positional one.
     */
    private function argumentLabel(Arg $arg, int $index): string
    {
        if ($arg->name instanceof Identifier) {
            return sprintf('"%s"', $arg->name->toString());
        }

        return sprintf('#%d', $index + 1);
    }

    /**
     * PHPStan visits a nullsafe method call twice — once per scope branch (null and non-null).
     * Only the first-level statement branch runs the method body, so analysing just that one
     * avoids duplicate errors for the same argument.
     */
    private function isNullBranchOfNullsafeCall(CallLike $node, Scope $scope): bool
    {
        return $node instanceof NullsafeMethodCall && !$scope->isInFirstLevelStatement();
    }

    /**
     * Builds a human-readable label for the call target such as `function X()`, `method C::m()`, or `constructor C`.
     */
    private function callLabel(CallLike $node, Scope $scope): string
    {
        if ($node instanceof FuncCall && $node->name instanceof Name) {
            return sprintf('function %s()', $node->name->toString());
        }

        if (($node instanceof MethodCall || $node instanceof NullsafeMethodCall) && $node->name instanceof Identifier) {
            return $this->methodCallLabel($node, $scope);
        }

        if ($node instanceof StaticCall && $node->class instanceof Name && $node->name instanceof Identifier) {
            return sprintf('method %s::%s()', $scope->resolveName($node->class), $node->name->toString());
        }

        if ($node instanceof New_) {
            return $this->constructorCallLabel($node, $scope);
        }

        return 'call';
    }

    /**
     * Builds a label for `new Class(...)` or `new class {}` expressions.
     */
    private function constructorCallLabel(New_ $node, Scope $scope): string
    {
        if ($node->class instanceof Class_) {
            return 'anonymous class constructor';
        }

        if ($node->class instanceof Name) {
            return sprintf('constructor %s', $scope->resolveName($node->class));
        }

        return 'call';
    }

    /**
     * Resolves the receiver type of an instance or nullsafe method call and builds a FQN-qualified label.
     */
    private function methodCallLabel(MethodCall|NullsafeMethodCall $node, Scope $scope): string
    {
        assert($node->name instanceof Identifier);

        $methodName = $node->name->toString();
        $classNames = $scope->getType($node->var)->getObjectClassNames();

        if ($classNames === []) {
            return sprintf('method %s()', $methodName);
        }

        return sprintf('method %s::%s()', implode('|', $classNames), $methodName);
    }
}
