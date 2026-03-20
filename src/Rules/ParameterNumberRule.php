<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/** @implements Rule<ClassMethod> */
final readonly class ParameterNumberRule implements Rule
{
    private int $maxParameters;

    private bool $ignoreOverridden;

    /**
     * @param array{
     *     ignoreOverridden?: bool
     * } $options
     */
    public function __construct(int $maxParameters = 3, array $options = [])
    {
        $this->maxParameters = $maxParameters;
        $this->ignoreOverridden = $options['ignoreOverridden'] ?? true;
    }

    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        if ($this->ignoreOverridden && $this->hasOverrideAttribute($node)) {
            return [];
        }

        $count = count($node->params);

        if ($count <= $this->maxParameters) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        $className = $classReflection !== null ? $classReflection->getName() : 'unknown';

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() has %d parameters. Maximum allowed is %d.',
                    $className,
                    $node->name->toString(),
                    $count,
                    $this->maxParameters,
                ),
            )
                ->identifier('haspadar.parameterNumber')
                ->build(),
        ];
    }

    /** Checks whether the method has the #[Override] attribute */
    private function hasOverrideAttribute(ClassMethod $node): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if ($attr->name->toString() === 'Override') {
                    return true;
                }
            }
        }

        return false;
    }
}
