<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Counts unique types a class depends on directly, including property types, method parameter types, return types, new expressions, static calls, and catch type hints.
 * Types listed in `excludedClasses` are not counted.
 *
 * @implements Rule<Class_>
 */
final readonly class CouplingBetweenObjectsRule implements Rule
{
    private const array SCALAR_TYPES = ['self', 'parent', 'static', 'void', 'null', 'bool', 'int', 'float', 'string', 'array', 'object', 'callable', 'iterable', 'never', 'mixed', 'true', 'false'];

    /** @var list<string> */
    private array $excludedClasses;

    private CouplingBetweenObjectsRule\TypeNameExtractor $extractor;

    private CouplingBetweenObjectsRule\MethodBodyTypeCollector $bodyCollector;

    /**
     * Constructs the rule with the given coupling limit and optional exclusion list.
     *
     * @param int $maximum Maximum number of unique dependent types allowed per class
     * @param array{
     *     excludedClasses?: list<string>
     * } $options Classes to exclude from the coupling count (case-insensitive FQCN match).
     */
    public function __construct(private int $maximum = 15, array $options = [])
    {
        $this->excludedClasses = $options['excludedClasses'] ?? [];
        $this->extractor = new CouplingBetweenObjectsRule\TypeNameExtractor();
        $this->bodyCollector = new CouplingBetweenObjectsRule\MethodBodyTypeCollector();
    }

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Class_ $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->name === null) {
            return [];
        }

        $count = count($this->collectTypes($node));

        if ($count <= $this->maximum) {
            return [];
        }

        $className = $node->name->toString();

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Class %s has a coupling between objects value of %d. Maximum allowed is %d.',
                    $className,
                    $count,
                    $this->maximum,
                ),
            )
                ->identifier('haspadar.couplingBetweenObjects')
                ->build(),
        ];
    }

    /**
     * Collects all unique type names from the class, excluding scalars and excluded classes.
     *
     * @return list<string>
     */
    private function collectTypes(Class_ $node): array
    {
        $names = array_merge(
            $this->collectPropertyTypes($node),
            $this->collectMethodTypes($node),
        );

        return $this->filterAndDeduplicate($names);
    }

    /**
     * Collects type names from property declarations.
     *
     * @return list<string>
     */
    private function collectPropertyTypes(Class_ $node): array
    {
        $names = [];

        foreach ($node->getProperties() as $property) {
            if ($property->type !== null) {
                $names = array_merge($names, $this->extractor->extract($property->type));
            }
        }

        return $names;
    }

    /**
     * Collects type names from all method signatures and bodies.
     *
     * @return list<string>
     */
    private function collectMethodTypes(Class_ $node): array
    {
        $names = [];

        foreach ($node->getMethods() as $method) {
            $names = array_merge($names, $this->collectSignatureTypes($method));
            $names = array_merge($names, $this->collectBodyTypes($method));
        }

        return $names;
    }

    /**
     * Collects type names from method parameter and return type declarations.
     *
     * @return list<string>
     */
    private function collectSignatureTypes(ClassMethod $method): array
    {
        $names = [];

        foreach ($method->params as $param) {
            if ($param->type !== null) {
                $names = array_merge($names, $this->extractor->extract($param->type));
            }
        }

        if ($method->returnType !== null) {
            $names = array_merge($names, $this->extractor->extract($method->returnType));
        }

        return $names;
    }

    /**
     * Collects type names from `new`, static calls, and `catch` inside a method body.
     *
     * @return list<string>
     */
    private function collectBodyTypes(ClassMethod $method): array
    {
        return $this->bodyCollector->collect($method);
    }

    /**
     * Filters out scalar types and excluded classes, then deduplicates the result.
     *
     * @param list<string> $names
     * @return list<string>
     */
    private function filterAndDeduplicate(array $names): array
    {
        $excluded = array_map('strtolower', $this->excludedClasses);
        $unique = [];

        foreach ($names as $name) {
            $lower = strtolower($name);

            if (in_array($lower, self::SCALAR_TYPES, true)) {
                continue;
            }

            if (in_array($lower, $excluded, true)) {
                continue;
            }

            $unique[$lower] = true;
        }

        return array_keys($unique);
    }
}
