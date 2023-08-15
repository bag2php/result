<?php

declare(strict_types=1);

namespace Bag2;

use Bag2\Result\Err;
use Bag2\Result\Ok;

abstract class Result
{
    /**
     * @phpstan-assert-if-true Ok $result
     * @pure
     */
    public static function isOk(Result $result): bool
    {
        return $result instanceof Ok;
    }

    /**
     * @phpstan-assert-if-true Err $result
     * @pure
     */
    public static function isErr(Result $result): bool
    {
        return $result instanceof Err;
    }
}
