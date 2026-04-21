<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule\CohesionGraph;
use Override;
use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports a class whose methods split into more disjoint groups than allowed (LCOM4).
 *
 * Builds an undirected graph over the class's own non-magic methods: two methods are connected
 * when they touch at least one common instance or static property, or one calls the other via
 * `$this->method()`, `self::method()` or `static::method()`. The number of connected components
 * is the LCOM4 value. A cohesive class has exactly one component; a class exceeding `$maxLcom`
 * is reported as lacking cohesion.
 *
 * Abstract, anonymous and excluded classes are skipped. Classes with fewer methods than
 * `$minMethods` or fewer properties than `$minProperties` are skipped — on such classes LCOM
 * degenerates and carries no signal. Both regular properties and promoted constructor parameters
 * count toward `$minProperties`. Constructors, destructors and PHP magic methods are excluded
 * from the graph.
 *
 * @implements Rule<Class_>
 */
final readonly class LackOfCohesionRule implements Rule
{
    private const int DEFAULT_MIN_METHODS = 7;

    private const int DEFAULT_MIN_PROPERTIES = 3;

    private const array EXCLUDED_METHOD_NAMES = [
        '__construct',
        '__destruct',
        '__get',
        '__set',
        '__isset',
        '__unset',
        '__tostring',
        '__invoke',
        '__clone',
        '__call',
        '__callstatic',
        '__sleep',
        '__wakeup',
        '__serialize',
        '__unserialize',
        '__debuginfo',
        '__set_state',
    ];

    private int $minMethods;

    private int $minProperties;

    /** @var list<string> */
    private array $excludedClasses;

    /**
     * Constructs the rule with the LCOM threshold and filter options.
     *
     * @param array{
     *     minMethods?: int,
     *     minProperties?: int,
     *     excludedClasses?: list<string>
     * } $options
     */
    public function __construct(private int $maxLcom = 1, array $options = [])
    {
        $this->minMethods = $options['minMethods'] ?? self::DEFAULT_MIN_METHODS;
        $this->minProperties = $options['minProperties'] ?? self::DEFAULT_MIN_PROPERTIES;
        $this->excludedClasses = array_map(
            static fn(string $class): string => strtolower(ltrim($class, '\\')),
            $options['excludedClasses'] ?? [],
        );
    }

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the class and reports when LCOM4 exceeds the configured limit.
     *
     * @psalm-param Class_ $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isAnonymous() || $node->isAbstract() || $node->name === null) {
            return [];
        }

        $className = $node->name->toString();

        if ($this->isExcluded($className, $scope->getNamespace() ?? '')) {
            return [];
        }

        $methods = $this->eligibleMethods($node);

        if (count($methods) < $this->minMethods || $this->propertyCount($node) < $this->minProperties) {
            return [];
        }

        $lcom = (new CohesionGraph())->componentCount($methods);

        if ($lcom <= $this->maxLcom) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Class %s splits into %d disjoint method groups (LCOM4). Maximum allowed is %d.',
                    $className,
                    $lcom,
                    $this->maxLcom,
                ),
            )
                ->identifier('haspadar.lackOfCohesion')
                ->build(),
        ];
    }

    /**
     * Tells whether the class's FQCN is listed in `excludedClasses`.
     */
    private function isExcluded(string $className, string $namespace): bool
    {
        $fqcn = strtolower(ltrim(sprintf('%s\\%s', $namespace, $className), '\\'));

        return in_array($fqcn, $this->excludedClasses, true);
    }

    /**
     * Returns the methods that participate in the cohesion graph.
     *
     * @return list<ClassMethod>
     */
    private function eligibleMethods(Class_ $node): array
    {
        $methods = [];

        foreach ($node->getMethods() as $method) {
            if (in_array(strtolower($method->name->toString()), self::EXCLUDED_METHOD_NAMES, true)) {
                continue;
            }

            $methods[] = $method;
        }

        return $methods;
    }

    /**
     * Counts regular properties plus promoted constructor parameters.
     */
    private function propertyCount(Class_ $node): int
    {
        $count = count($node->getProperties());
        $constructor = $node->getMethod('__construct');

        if ($constructor === null) {
            return $count;
        }

        $visibilityMask = Modifiers::PUBLIC | Modifiers::PROTECTED | Modifiers::PRIVATE;

        foreach ($constructor->params as $param) {
            if (($param->flags & $visibilityMask) !== 0) {
                $count++;
            }
        }

        return $count;
    }
}
