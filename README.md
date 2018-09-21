# A card which lets you batch import resources

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sparclex/nova-import-card.svg?style=flat-square)](https://packagist.org/packages/sparclex/nova-import-card)
[![Total Downloads](https://img.shields.io/packagist/dt/sparclex/nova-import-card.svg?style=flat-square)](https://packagist.org/packages/sparclex/nova-import-card)


A customizable import card for laravel nova.

Add a screenshot of the tool here.

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require sparclex/nova-import-card
```

Next up, you must register the card. This is typically done in the `card` method of the corresponding resource or the 
`NovaServiceProvider`.

```php
// in app/Providers/NovaServiceProvider.php or app/Nova/<Resource>.php

// ...

public function card()
{
    return [
        // ...
        new \Sparclex\NovaImportCard\NovaImportCard(App\Nova\User::class),
    ];
}
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
