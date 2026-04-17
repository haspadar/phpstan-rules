<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\FewAfferent;

interface TargetContract
{
    public function tag(): string;
}

interface Taggable extends TargetContract
{
}

trait Stampable
{
    public function stamp(): string
    {
        return 'stamp';
    }
}

abstract class BaseTarget implements Taggable
{
    use Stampable;

    public function tag(): string
    {
        return 'base';
    }
}

final class Target extends BaseTarget
{
    public Taggable $sibling;

    public static function make(): self
    {
        return new \Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\FewAfferent\Target();
    }
}

final class UserA
{
    public function __construct(private readonly Target $target)
    {
    }

    public function handle(self $same, int $count): ?Target
    {
        $copy = new Target();

        return $count > 0 ? $copy : $this->target;
    }
}

final class UserB
{
    public function __construct(private readonly Target $target)
    {
    }

    public function describe(Target $other): string
    {
        return $other === $this->target ? 'same' : 'other';
    }
}

final class UserC
{
    public function wrap(): Taggable
    {
        return new class implements Taggable {
            public function tag(): string
            {
                return 'anon';
            }
        };
    }
}
