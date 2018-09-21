# A card which lets you batch import resources

[![Latest Stable Version](https://poser.pugx.org/sparclex/nova-import-card/v/stable)](https://packagist.org/packages/sparclex/nova-import-card)
[![Total Downloads](https://poser.pugx.org/sparclex/nova-import-card/downloads)](https://packagist.org/packages/sparclex/nova-import-card)
[![Latest Unstable Version](https://poser.pugx.org/sparclex/nova-import-card/v/unstable)](https://packagist.org/packages/sparclex/nova-import-card)
[![License](https://poser.pugx.org/sparclex/nova-import-card/license)](https://packagist.org/packages/sparclex/nova-import-card)


A customizable import card for laravel nova.

![Nova Import Card Screenshot](https://raw.githubusercontent.com/sparclex/screenshots/master/nova-import-card-resource-index.png)
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
