# EnvInject

Inject/interpolate environment variables in strings.

- Substitutes raw environment variables in strings
- Supports default values if environment variable is not set
- Supports escaping JSON strings
- Supports a callback to write your own value modifiers / escapers

## Install

```
composer require 24hoursmedia/php-env-inject
```

## Usage

### Interpolate env vars in a string

```php
<?php
use T4\EnvInject\EnvInject;
putenv('YOUR_NAME=John Doe');

echo EnvInject::interpolate('Hello ${YOUR_NAME}! ${MESSAGE:-Have a nice day!}');
// Hello John Doe! Have a nice day!
```

### Interpolation in JSON strings

Escape json values with `JsonEnvInject::interpolate()`:

Substituting raw environment variables in JSON strings can result in failures
if you have special characters in your values such as quotes.
The JsonEnvInject class can escape JSON strings for you before interpolation.

```php
<?php
use T4\EnvInject\JsonEnvInject;
putenv('FOO=f"o"o');
echo JsonEnvInject::interpolate('{"foo":"${FOO}"}');
// {"foo":"f\"o\"o"}
```

### Modify values with a callback function

Use `EnvInject::interpolateWithCallback(string $string, Closure $callback)` to
modify values with a callback function.

The callback function receives the value as first argument and the key as second argument.
It should return the modified value to interpolate.

Use this to create your own escape functions or more complex modifiers.

## Develop and run tests

Open project in a docker container:

    docker run -it --rm -v $(pwd):/app -w /app php:8.0-cli-alpine /bin/sh
    apk add php-curl php-mbstring php-openssl php-zip php-phar
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
    composer install

    # run tests
    ./vendor/bin/phpunit tests --testdox



