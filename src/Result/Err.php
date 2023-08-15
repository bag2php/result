<?php

namespace Bag2\Result;

use Bag2\Result;
use Generator;

/**
 * @template E
 */
final class Err extends Result
{
    public function __construct(
        /** @phpstan-var E */
        private mixed $value
    ) {
    }

    /**
     * @phpstan-return array{}
     */
    public function getOk(): array
    {
        return [];
    }

    /**
     * @phpstan-return array{E}
     */
    public function getErr(): array
    {
        return [$this->value];
    }

    /**
     * @phpstan-return E
     * @pure
     */
    public function unwrapErr()
    {
        return $this->value;
    }

    /**
     * @template U
     * @phpstan-param U $default
     * @phpstan-return Generator<U>
     * @pure
     */
    public function or($default): Generator
    {
        yield $default;
    }
}
