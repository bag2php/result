# Bag2\Result

This package provides an implementation of the `Result` class, which consists of `Ok` and `Err`.

## Install

```sh
composer require bag2php/result
```

This package is optimized for the latest PHPStan.

## Example

Consider an email validation feature: This function reports the reason for the validation failure to prompt the user to re-enter their email address.

```php
use Bag2\Result;

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
```

Let's call this function:

```php
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
```

If you're using PHPStan, the following code also works type-safely:

```php
$input = filter_var($_POST['email'] ?? null, FILTER_DEFAULT, FILTER_NULL_ON_FAILURE) ?? '';
$failure = validateEmail($input)->getErr()[0] ?? null;
// Type: $failure = array{message: string}|null

if ($failure === null) {
    $status = 200;
    $response = [
        'success' => true,
    ];
} else {
    $status = 400;
    $response = [
        'success' => false,
        'reason' => $failure, // Type: $failure = array{message: string}
    ];
}
```

## Design

### Why not `Result<T, E>`?

Currently PHPStan requires a special extension to create this type. `Ok<T>|Err<E>` is simpler because union types are more friendly to PHP.

### Shouldn't it just be `T|E`?

If the caller can always ensure proper type-checking, it may seem sufficient, but in practice, users can get their values mixed up if they type-check incorrectly.

Wrapping the value in Result prevents accessing to the wrong type. It also supports arbitrary classes as well as other types supported by PHPStan (eg constant types and array-shapes).

### What about `Psl\Result`?

[azjezz/psl](https://github.com/azjezz/psl) is a library inspired by [Hack Standard Library](https://github.com/hhvm/hsl) and provides its own [Result classes](https://github.com/azjezz/psl/tree/next/src/Psl/Result).

But `Psl\Result\Failure` tends to throw exceptions casually.

## Copyright

This package is licenced under [Apache License 2.0][Apache-2.0].

> Copyright 2019 Baguette HQ
>
> Licensed under the Apache License, Version 2.0 (the "License");
> you may not use this file except in compliance with the License.
> You may obtain a copy of the License at
>
>     http://www.apache.org/licenses/LICENSE-2.0
>
> Unless required by applicable law or agreed to in writing, software
> distributed under the License is distributed on an "AS IS" BASIS,
> WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
> See the License for the specific language governing permissions and
> limitations under the License.

[Apache-2.0]: https://www.apache.org/licenses/LICENSE-2.0
