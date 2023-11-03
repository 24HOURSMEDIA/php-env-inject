# EnvInject

Inject/interpolate environment variables in strings.

## Install

```
composer require 24hoursmedia/php-env-inject
```

## Usage

```php
<?php
use T4\EnvInject\EnvInject;
putenv('YOUR_NAME=John Doe');

echo EnvInject::interpolate('Hello ${YOUR_NAME}! ${MESSAGE:-Have a nice day!}');
// Hello John Doe! Have a nice day!
```


## Develop and run tests

Open project in a docker container:

    docker run -it --rm -v $(pwd):/app -w /app php:8.0-cli-alpine /bin/sh
    apk add php-curl php-mbstring php-openssl php-zip php-phar
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
    composer install

    # run tests
    ./vendor/bin/phpunit tests --testdox



