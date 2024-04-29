# Code Standars for Weblabor

[![Latest Version on Packagist](https://img.shields.io/packagist/v/weblabormx/weblabor-cs.svg?style=flat-square)](https://packagist.org/packages/weblabormx/weblabor-cs)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/weblabormx/weblabor-cs/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/weblabormx/weblabor-cs/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/weblabormx/weblabor-cs/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/weblabormx/weblabor-cs/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/weblabormx/weblabor-cs.svg?style=flat-square)](https://packagist.org/packages/weblabormx/weblabor-cs)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/weblabor-cs.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/weblabor-cs)

## Installation

You can install the package via composer:

```bash
composer require weblabormx/weblabor-cs
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="weblabor-cs-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="weblabor-cs-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="weblabor-cs-views"
```

## Usage

```php
$codeStandars = new Weblabor\CodeStandars();
echo $codeStandars->echoPhrase('Hello, Weblabor!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Carlos Escobar](https://github.com/weblabormx)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
