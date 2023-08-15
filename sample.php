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

/**
 * @return Result\Ok<non-empty-string>|Result\Err<array{message: string}>
 */
function validateEmail(string $email): Result
{
    $filtered = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($filtered === false) {
        return new Result\Err([
            'message' => '$email is malformed address.',
        ]);
    }

    $ip = filter_var(explode('@', $filtered)[1], FILTER_VALIDATE_IP);
    if ($ip !== false) {
        return new Result\Err([
            'message' => 'IP addresses are not allowed in $email.',
        ]);
    }

    assert($filtered !== '');

    return new Result\Ok($filtered);
}

$input = filter_var($_POST['email'] ?? null, FILTER_DEFAULT, FILTER_NULL_ON_FAILURE) ?? '';
$result = validateEmail($input);

if (Result::isOk($result)) {
    $status = 200;
    $response = [
        'success' => true,
    ];
} else {
    $status = 400;
    $response = [
        'success' => false,
        'reason' => $result->unwrapErr(),
    ];
}

$failure = validateEmail($input)->getErr()[0] ?? null;
\PHPStan\dumpType($failure); // array{message: string}|null

if ($failure === null) {
    $status = 200;
    $response = [
        'success' => true,
    ];
} else {
    \PHPStan\dumpType($failure); // array{message: string}
    $status = 400;
    $response = [
        'success' => false,
        'reason' => $failure,
    ];
}
