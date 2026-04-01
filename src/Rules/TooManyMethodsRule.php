<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/** @implements Rule<Class_> */
final readonly class TooManyMethodsRule implements Rule
{
    private bool $onlyPublic;

    /**
     * @param array{
     *     onlyPublic?: bool
     * } $options
     */
    public function __construct(private int $maxMethods = 20, array $options = [])
    {
        $this->onlyPublic = $options['onlyPublic'] ?? false;
    }

    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var Class_ $node */
        $methods = $node->getMethods();

        if ($this->onlyPublic) {
            $methods = array_filter($methods, static fn(Node\Stmt\ClassMethod $method) => $method->isPublic());
        }

        $count = count($methods);

        if ($count <= $this->maxMethods) {
            return [];
        }

        $className = $node->name !== null
            ? $node->name->toString()
            : 'anonymous';

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
