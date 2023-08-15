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
 * @return Result\Ok<non-empty-string>|Result\Err<array{message: string}>>
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

    return new Result\Ok($email);
}
```

Let's call this function:

```php
$input = filter_var($_POST['email']);
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
$input = filter_var($_POST['email']);
$failure = validateEmail($input)->getErr()[0] ?? null;

if ($failure === null) {
    $status = 200;
    $response = [
        'success' => true,
    ];
} else {
    $status = 400;
    $response = [
        'success' => false,
        'reason' => $failure,
    ];
}
```

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
