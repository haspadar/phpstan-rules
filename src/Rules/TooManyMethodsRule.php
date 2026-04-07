<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports a class that has more methods than the configured maximum.
 *
 * @implements Rule<Class_>
 */
final readonly class TooManyMethodsRule implements Rule
{
    private bool $onlyPublic;

    /**
     * Constructs the rule with the given method limit and options.
     *
     * @param array{
     *     onlyPublic?: bool
     * } $options
     */
    public function __construct(private int $maxMethods = 20, array $options = [])
    {
        $this->onlyPublic = $options['onlyPublic'] ?? false;
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
        $methods = $node->getMethods();

        if ($this->onlyPublic) {
            $methods = array_filter($methods, static fn(Node\Stmt\ClassMethod $method) => $method->isPublic());
        }

        $count = count($methods);

        if ($count <= $this->maxMethods) {
            return [];
        }

        if ($node->name === null) {
            throw new ShouldNotHappenException();
        }

        $className = $node->name->toString();

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Class %s has %d methods. Maximum allowed is %d.',
                    $className,
                    $count,
                    $this->maxMethods,
                ),
            )
                ->identifier('haspadar.tooManyMethods')
                ->build(),
        ];
    }
}
