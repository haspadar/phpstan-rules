<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\HiddenFieldRule\LocalAssignmentCollector;
use Haspadar\PHPStanRules\Rules\HiddenFieldRule\ParamShadowDetector;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Detects parameters and local variables that shadow a class property.
 *
 * A parameter whose name matches a property of the enclosing class, or a
 * local `$var = ...` assignment using such a name, is reported. Promoted
 * constructor parameters are excluded since they are the property, not a
 * shadow. Locals inside nested closures, arrow functions, other functions,
 * and anonymous classes belong to a different scope and are not inspected.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class HiddenFieldRule implements Rule
{
    private bool $ignoreConstructorParameter;

    private bool $ignoreAbstractMethods;

    private bool $ignoreSetter;

    /** @var list<string> */
    private array $ignoreNames;

    /**
     * Constructs the rule with collaborators and configuration options.
     *
     * @param LocalAssignmentCollector $collector Collects top-level $var=... assignments from a method body
     * @param ParamShadowDetector $paramDetector Inspects method parameters for shadowing names
     * @param array{
     *     ignoreConstructorParameter?: bool,
     *     ignoreAbstractMethods?: bool,
     *     ignoreSetter?: bool,
     *     ignoreNames?: list<string>
     * } $options Flags that exempt constructor, abstract, setter, and named parameters or locals from the shadow check
     */
    public function __construct(
        private LocalAssignmentCollector $collector,
        private ParamShadowDetector $paramDetector,
        array $options = [],
    ) {
        $this->ignoreConstructorParameter = $options['ignoreConstructorParameter'] ?? true;
        $this->ignoreAbstractMethods = $options['ignoreAbstractMethods'] ?? false;
        $this->ignoreSetter = $options['ignoreSetter'] ?? false;
        $this->ignoreNames = $options['ignoreNames'] ?? [];
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param ClassMethod $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($this->shouldSkipMethod($node, $scope)) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        assert($classReflection !== null);
        $propertyNames = $this->collectPropertyNames($classReflection);

        if ($propertyNames === []) {
            return [];
        }

        $context = [
            'className' => $classReflection->getDisplayName(),
            'methodName' => $node->name->toString(),
            'propertyNames' => $propertyNames,
        ];

        return [
            ...$this->paramErrors($node, $context),
            ...$this->localErrors($node, $context),
        ];
    }

    /**
     * Returns true if the method should not be analysed at all.
     */
    private function shouldSkipMethod(ClassMethod $node, Scope $scope): bool
    {
        if ($scope->getClassReflection() === null) {
            return true;
        }

        if ($this->ignoreAbstractMethods && $node->isAbstract()) {
            return true;
        }

        return $this->ignoreSetter && $this->looksLikeSetter($node->name->toString());
    }

    /**
     * Reports parameters that shadow a property.
     *
     * @param array{className: string, methodName: string, propertyNames: list<string>} $context
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function paramErrors(ClassMethod $node, array $context): array
    {
        if ($context['methodName'] === '__construct' && $this->ignoreConstructorParameter) {
            return [];
        }

        $errors = [];

        foreach ($this->paramDetector->detect($node, $context['propertyNames'], $this->ignoreNames) as [$name, $line]) {
            $errors[] = $this->buildError(
                sprintf(
                    'Parameter $%s in %s::%s() shadows property of the same name. Rename to avoid the name collision.',
                    $name,
                    $context['className'],
                    $context['methodName'],
                ),
                $line,
            );
        }

        return $errors;
    }

    /**
     * Reports local variables that shadow a property.
     *
     * @param array{className: string, methodName: string, propertyNames: list<string>} $context
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function localErrors(ClassMethod $node, array $context): array
    {
        $skip = array_merge($this->paramDetector->paramNames($node), $this->ignoreNames);
        $errors = [];

        foreach ($this->collector->collect($node) as [$varName, $line]) {
            if (!in_array($varName, $context['propertyNames'], true) || in_array($varName, $skip, true)) {
                continue;
            }

            $errors[] = $this->buildError(
                sprintf(
                    'Local variable $%s in %s::%s() shadows property of the same name. Rename to avoid the name collision.',
                    $varName,
                    $context['className'],
                    $context['methodName'],
                ),
                $line,
            );
        }

        return $errors;
    }

    /**
     * Builds a PHPStan error with the rule identifier and line.
     *
     * @throws ShouldNotHappenException
     */
    private function buildError(string $message, int $line): IdentifierRuleError
    {
        return RuleErrorBuilder::message($message)
            ->identifier('haspadar.hiddenField')
            ->line($line)
            ->build();
    }

    /**
     * Returns native property names declared on the class (both non-static and static).
     *
     * @return list<string>
     */
    private function collectPropertyNames(ClassReflection $classReflection): array
    {
        $names = [];

        foreach ($classReflection->getNativeReflection()->getProperties() as $property) {
            if ($property->getDeclaringClass()->getName() !== $classReflection->getName()) {
                continue;
            }

            $names[] = $property->getName();
        }

        return $names;
    }

    /**
     * Returns true if the method name looks like a setter (setX convention).
     */
    private function looksLikeSetter(string $methodName): bool
    {
        return preg_match('/^set[A-Z]/', $methodName) === 1;
    }
}
