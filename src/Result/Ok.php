<?php

namespace Bag2\Result;

use Bag2\Result;
use Generator;
use IteratorAggregate;
use Traversable;

/**
 * @template T
 * @implements IteratorAggregate<T>
 */
final class Ok extends Result implements IteratorAggregate
{
    public function __construct(
        /** @phpstan-var T */
        private mixed $value
    ) {
    }

    /**
     * @phpstan-return array{T}
     */
    public function getOk(): array
    {
        return [$this->value];
    }

    /**
     * @phpstan-return array{}
     */
    public function getErr(): array
    {
        return [];
    }

    /**
     * @phpstan-return T
     * @pure
     */
    public function unwrap()
    {
        return $this->value;
    }

    public function getIterator(): Traversable
    {
        yield $this->value;
    }

    /**
     * @template U
     * @phpstan-param U $default
     * @phpstan-return Generator<T|U>
     * @pure
     */
    public function or($default): Generator
    {
        yield $this->value;
    }
}
