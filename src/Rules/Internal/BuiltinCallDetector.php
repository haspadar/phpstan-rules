<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\Internal;

use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;

/**
 * Detects whether a call expression targets a PHP built-in function, method, or class.
 *
 * Dynamic calls where the target cannot be resolved are reported as built-in so that
 * rules using this detector skip vendor-sensitive code paths by default.
 */
final readonly class BuiltinCallDetector
{
    /**
     * Accepts the PHPStan reflection provider used to resolve call targets.
     *
     * @param ReflectionProvider $reflectionProvider Shared PHPStan reflection used to resolve call targets
     */
    public function __construct(private ReflectionProvider $reflectionProvider) {}

    /**
     * Returns true when the call targets a PHP built-in function, method, or class.
     *
     * @param CallLike $node Call expression under analysis
     * @param Scope $scope PHPStan scope providing type information for the call
     */
    public function isBuiltin(CallLike $node, Scope $scope): bool
    {
        if ($node instanceof FuncCall) {
            return $this->isBuiltinFunction($node, $scope);
        }

        if ($node instanceof MethodCall || $node instanceof NullsafeMethodCall) {
            return $this->isBuiltinMethod($node, $scope);
        }

        if ($node instanceof StaticCall) {
            return $this->isBuiltinStatic($node, $scope);
        }

        if ($node instanceof New_) {
            return $this->isBuiltinConstructor($node, $scope);
        }

        return true;
    }

    /**
     * Resolves a function call and checks whether the target is a PHP built-in.
     */
    private function isBuiltinFunction(FuncCall $node, Scope $scope): bool
    {
        if (!$node->name instanceof Name) {
            return true;
        }

        if (!$this->reflectionProvider->hasFunction($node->name, $scope)) {
            return true;
        }

        return $this->reflectionProvider->getFunction($node->name, $scope)->isBuiltin();
    }

    /**
     * Resolves an instance or nullsafe method call and checks whether every declaring class is a PHP built-in.
     *
     * For a union receiver where several classes declare the same method, any user-defined declaring
     * class makes the call user-defined so that the rule still inspects its arguments.
     */
    private function isBuiltinMethod(MethodCall|NullsafeMethodCall $node, Scope $scope): bool
    {
        if (!$node->name instanceof Identifier) {
            return true;
        }

        $methodName = $node->name->toString();
        $foundAny = false;

        foreach ($scope->getType($node->var)->getObjectClassReflections() as $classReflection) {
            if (!$classReflection->hasMethod($methodName)) {
                continue;
            }

            $foundAny = true;

            if (!$classReflection->getMethod($methodName, $scope)->getDeclaringClass()->isBuiltin()) {
                return false;
            }
        }

        return !$foundAny;
    }

    /**
     * Resolves a static method call and checks whether the declaring class is a PHP built-in.
     */
    private function isBuiltinStatic(StaticCall $node, Scope $scope): bool
    {
        if (!$node->class instanceof Name || !$node->name instanceof Identifier) {
            return true;
        }

        $className = $scope->resolveName($node->class);

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        if (!$classReflection->hasMethod($node->name->toString())) {
            return true;
        }

        return $classReflection->getMethod($node->name->toString(), $scope)
            ->getDeclaringClass()
            ->isBuiltin();
    }

    /**
     * Resolves a constructor call and checks whether the instantiated class is a PHP built-in.
     *
     * Anonymous classes are always user-defined, so `new class(null) {}` is inspected by the rule.
     * Dynamic `new` where the class is an arbitrary expression cannot be resolved and is treated as built-in.
     */
    private function isBuiltinConstructor(New_ $node, Scope $scope): bool
    {
        if ($node->class instanceof Class_) {
            return false;
        }

        if (!$node->class instanceof Name) {
            return true;
        }

        $className = $scope->resolveName($node->class);

        if (!$this->reflectionProvider->hasClass($className)) {
            return true;
        }

        return $this->reflectionProvider->getClass($className)->isBuiltin();
    }
}
