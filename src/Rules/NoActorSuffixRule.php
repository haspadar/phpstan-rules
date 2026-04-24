<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Forbids actor-like class name suffixes ending with -er or -or.
 *
 * A class whose name ends with -er or -or is rejected unless (a) its last
 * PascalCase word is on the allowedWords whitelist of real noun-entities
 * (User, Order, Number, Error, ...), or (b) any of its transitive parent
 * classes, implemented interfaces, or used traits lives under a namespace
 * prefix from excludedParentNamespaces (Symfony\, Illuminate\, ...), or
 * (c) its fully qualified name is in excludedClasses. Anonymous classes
 * are never reported.
 *
 * @implements Rule<Class_>
 */
final readonly class NoActorSuffixRule implements Rule
{
    /** @var list<string> */
    private array $allowedWords;

    /** @var list<string> */
    private array $excludedParentNamespaces;

    /** @var list<string> */
    private array $excludedClasses;

    /**
     * Constructs the rule with the whitelist and framework exclusions.
     *
     * @param ReflectionProvider $reflectionProvider PHPStan reflection provider used to resolve ancestor classes
     * @param array{
     *     allowedWords?: list<string>,
     *     excludedParentNamespaces?: list<string>,
     *     excludedClasses?: list<string>
     * } $options Whitelist of real noun-entities ending in -er/-or, framework namespace prefixes, and FQCN exclusions
     */
    public function __construct(private ReflectionProvider $reflectionProvider, array $options = [])
    {
        $this->allowedWords = $options['allowedWords'] ?? [];
        $this->excludedParentNamespaces = $options['excludedParentNamespaces'] ?? [];
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
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Class_ $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isAnonymous() || $node->name === null || $node->namespacedName === null) {
            return [];
        }

        $className = $node->name->toString();

        if (!$this->endsWithActorSuffix($className)) {
            return [];
        }

        $fqcn = $node->namespacedName->toString();

        if (in_array(strtolower($fqcn), $this->excludedClasses, true)) {
            return [];
        }

        $lastWord = $this->lastPascalCaseWord($className);

        if (in_array($lastWord, $this->allowedWords, true)) {
            return [];
        }

        if ($this->hasExcludedParent($fqcn)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    "Class %s must not end with actor suffix '%s'. Classes are nouns, not procedures. Rename to a domain noun, or see README for when to extend allowedWords / excludedParentNamespaces.",
                    $className,
                    $lastWord,
                ),
            )
                ->identifier('haspadar.noActorSuffix')
                ->build(),
        ];
    }

    /**
     * Checks whether the class name ends with lowercase -er or -or, as produced by PascalCase naming.
     */
    private function endsWithActorSuffix(string $className): bool
    {
        return preg_match('/(?:er|or)$/', $className) === 1;
    }

    /**
     * Returns the last PascalCase word of the class name.
     *
     * Splits on lowercase-to-uppercase transitions and acronym-to-word
     * boundaries so that HTTPParameter yields "Parameter" and UserManager
     * yields "Manager".
     */
    private function lastPascalCaseWord(string $className): string
    {
        $words = preg_split('/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/', $className);

        if ($words === false) {
            return $className;
        }

        return $words[count($words) - 1];
    }

    /**
     * Returns true if any ancestor FQCN starts with one of the excluded namespace prefixes.
     */
    private function hasExcludedParent(string $fqcn): bool
    {
        if ($this->excludedParentNamespaces === []) {
            return false;
        }

        if (!$this->reflectionProvider->hasClass($fqcn)) {
            return false;
        }

        foreach ($this->collectAncestorNames($this->reflectionProvider->getClass($fqcn)) as $ancestorName) {
            foreach ($this->excludedParentNamespaces as $prefix) {
                if (str_starts_with($ancestorName, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Collects FQCNs of all transitive parents, interfaces, and traits.
     *
     * @return list<string>
     */
    private function collectAncestorNames(ClassReflection $classReflection): array
    {
        $names = [];

        foreach ($classReflection->getParents() as $parent) {
            $names[] = $parent->getName();
        }

        foreach ($classReflection->getInterfaces() as $interface) {
            $names[] = $interface->getName();
        }

        foreach ($classReflection->getTraits(true) as $trait) {
            $names[] = $trait->getName();
        }

        return $names;
    }
}
