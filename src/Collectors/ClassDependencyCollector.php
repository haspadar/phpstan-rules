<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Collectors;

use Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule\MethodBodyTypeCollector;
use Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule\TypeNameExtractor;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;

/**
 * Collects outbound class dependencies for every class and interface in the analysed codebase.
 *
 * For each class-like declaration, emits a tuple of its FQCN, kind (class/interface),
 * abstractness flag, and the list of FQCNs it directly references through:
 * property types, method signatures, `extends`, `implements`, `new`, static calls,
 * and `catch` type hints. The data is consumed by a `Rule<CollectedDataNode>` that
 * inverts the graph to compute afferent coupling (Ca).
 *
 * Self-references (`self`, `static`, `parent`) and scalar types are filtered out.
 * Traits are not collected — their code merges into using classes.
 *
 * @implements Collector<Node\Stmt\ClassLike, array{class: string, kind: string, abstract: bool, line: int, dependencies: list<string>}>
 */
final readonly class ClassDependencyCollector implements Collector
{
    private const array SCALAR_TYPES = ['self', 'parent', 'static', 'void', 'null', 'bool', 'int', 'float', 'string', 'array', 'object', 'callable', 'iterable', 'never', 'mixed', 'true', 'false'];

    private TypeNameExtractor $extractor;

    private MethodBodyTypeCollector $bodyCollector;

    /** Initializes reusable type-extraction helpers. */
    public function __construct()
    {
        $this->extractor = new TypeNameExtractor();
        $this->bodyCollector = new MethodBodyTypeCollector();
    }

    #[Override]
    public function getNodeType(): string
    {
        return Node\Stmt\ClassLike::class;
    }

    /**
     * Returns the class FQCN, kind, abstractness, declaration line, and the list of classes it depends on.
     *
     * @return array{class: string, kind: string, abstract: bool, line: int, dependencies: list<string>}|null
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): ?array
    {
        if (!$node instanceof Class_ && !$node instanceof Interface_) {
            return null;
        }

        if ($node->namespacedName === null) {
            return null;
        }

        $fqcn = $node->namespacedName->toString();
        $names = $this->collectHeaderTypes($node);

        foreach ($node->getMethods() as $method) {
            $names = array_merge($names, $this->collectMethodTypes($method));
        }

        return [
            'class' => $fqcn,
            'kind' => $node instanceof Interface_ ? 'interface' : 'class',
            'abstract' => $node instanceof Class_ && $node->isAbstract(),
            'line' => $node->getStartLine(),
            'dependencies' => $this->normalize($names, $fqcn),
        ];
    }

    /**
     * Returns type names referenced in extends/implements and property declarations.
     *
     * @return list<string>
     */
    private function collectHeaderTypes(Class_|Interface_ $node): array
    {
        if ($node instanceof Interface_) {
            return array_values(
                array_map(static fn(Node\Name $parent): string => $parent->toString(), $node->extends),
            );
        }

        $names = [];

        if ($node->extends !== null) {
            $names[] = $node->extends->toString();
        }

        foreach ($node->implements as $interfaceName) {
            $names[] = $interfaceName->toString();
        }

        foreach ($node->getProperties() as $property) {
            if ($property->type !== null) {
                $names = array_merge($names, $this->extractor->extract($property->type));
            }
        }

        return $names;
    }

    /**
     * Returns the list of type names referenced inside a method signature and body.
     *
     * @return list<string>
     */
    private function collectMethodTypes(ClassMethod $method): array
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

        return array_merge($names, $this->bodyCollector->collect($method));
    }

    /**
     * Filters scalars and the declaring class itself, then deduplicates.
     *
     * @param list<string> $names
     * @return list<string>
     */
    private function normalize(array $names, string $selfFqcn): array
    {
        $selfLower = strtolower($selfFqcn);
        $result = [];

        foreach ($names as $name) {
            $lower = strtolower($name);

            if (in_array($lower, self::SCALAR_TYPES, true)) {
                continue;
            }

            if ($lower === $selfLower) {
                continue;
            }

            $result[$lower] = $name;
        }

        return array_values($result);
    }
}
