<?php

declare(strict_types=1);

namespace Bag2\PHPStan\Result;

use Bag2\Result;
use PHPStan\Reflection\AllowedSubTypesClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;

class ResultSubtypesExtension implements AllowedSubTypesClassReflectionExtension
{
    public function supports(ClassReflection $classReflection): bool
    {
        return $classReflection->getName() === Result::class;
    }

    public function getAllowedSubTypes(ClassReflection $classReflection): array
    {
        return [
            new ObjectType(Result\Err::class),
            new ObjectType(Result\Ok::class),
        ];
    }
}
