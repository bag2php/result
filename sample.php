<?php

use Bag2\Result;

class User
{
}

class Admin extends User
{
}

/**
 * @return Result\Ok<Admin>|Result\Err<array{message: string}>
 */
function ensureAdmin(User $user): Result
{
    if ($user instanceof Admin) {
        return new Result\Ok($user);
    }

    return new Result\Err([
        'message' => '$user is not a admin',
    ]);
}

$user = new User();

$result = ensureAdmin($user);

if (Result::isOk($result)) {
    \PHPStan\dumpType($result); // => Ok<Admin>

    $ok = $result->unwrap(); // Admin
} else {
    \PHPStan\dumpType($result); // => Err<array{message: string}>

    $error = $result->unwrapErr();
    \PHPStan\dumpType($error); // => array{message: string}
}

$defaultAdmin = new Admin();

foreach ($result->mapOr($defaultAdmin) as $r) {
    \PHPStan\dumpType($r);
}
