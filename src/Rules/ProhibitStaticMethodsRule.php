<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Detects static method declarations and reports an error for each one.
 * Static methods are not polymorphic, cannot work with object state, and create hard dependencies
 * that make code difficult to test. Private and protected static helpers are treated the same as
 * public ones because they still encode procedural logic that bypasses the object lifecycle.
 *
 * When `onlyPublic` option is true, only public static methods are reported — mirroring Qulice's
 * `ProhibitPublicStaticMethods` behaviour and allowing private/protected static helpers.
 *
 * When `allowNamedConstructors` option is true, a static method is allowed if and only if it is a
 * named constructor: return type `self` or `static`, body consisting of exactly one
 * `return new self(...)` or `return new static(...)` statement. Static methods that return
 * `self`/`static` but contain any other logic are reported as invalid named constructors —
 * enforcing the cactoos "one primary constructor" principle.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ProhibitStaticMethodsRule implements Rule
{
    private bool $onlyPublic;

    private bool $allowNamedConstructors;

    /**
     * Constructs the rule with the given options.
     *
     * @param array{onlyPublic?: bool, allowNamedConstructors?: bool} $options Rule options: `onlyPublic` restricts checks to public static methods; `allowNamedConstructors` permits named constructors
     */
    public function __construct(array $options = [])
    {
        $this->onlyPublic = $options['onlyPublic'] ?? false;
        $this->allowNamedConstructors = $options['allowNamedConstructors'] ?? false;
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Reports an error when the analysed method declaration is static.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        if (!$node->isStatic()) {
            return [];
        }

        if ($this->onlyPublic && !$node->isPublic()) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection === null || $reflection->isAnonymous()
            ? 'class@anonymous'
            : $reflection->getName();
        $methodName = $node->name->toString();

        if ($this->allowNamedConstructors && $this->returnsSelfOrStatic($node)) {
            if ($this->isNamedConstructorBody($node)) {
                return [];
            }

            return [
                RuleErrorBuilder::message(
                    sprintf(
                        'Method %s::%s() is not a valid named constructor: body must be a single `return new self(...)` or `return new static(...)`. Move logic to the primary __construct().',
                        $className,
                        $methodName,
                    ),
                )
                    ->identifier('haspadar.namedConstructorBody')
                    ->build(),
            ];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() is static. Static methods are prohibited.',
                    $className,
                    $methodName,
                ),
            )
                ->identifier('haspadar.staticMethod')
                ->build(),
        ];
    }

    /**
     * Checks whether the method's return type is declared as `self`, `static`, `?self`, or `?static`.
     *
     * @param ClassMethod $node Analysed method
     * @return bool True when the return type (possibly nullable) is `self` or `static`
     */
    private function returnsSelfOrStatic(ClassMethod $node): bool
    {
        $returnType = $node->returnType;

        if ($returnType instanceof NullableType) {
            $returnType = $returnType->type;
        }

        if (!$returnType instanceof Name) {
            return false;
        }

        $name = $returnType->toLowerString();

        return $name === 'self' || $name === 'static';
    }

    /**
     * Checks whether the method body is exactly one `return new self(...)` or `return new static(...)`.
     *
     * @param ClassMethod $node Analysed method
     * @return bool True when the body matches the strict named-constructor shape
     */
    private function isNamedConstructorBody(ClassMethod $node): bool
    {
        $stmts = $node->stmts;

        if ($stmts === null || count($stmts) !== 1) {
            return false;
        }

        $only = $stmts[0];

        if (!$only instanceof Return_ || !$only->expr instanceof New_) {
            return false;
        }

        $class = $only->expr->class;

        if ($class instanceof Name) {
            $name = $class->toLowerString();

            return $name === 'self' || $name === 'static';
        }

        return false;
    }
}
