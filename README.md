# A card which lets you batch import resources

[![Latest Stable Version](https://poser.pugx.org/sparclex/nova-import-card/v/stable)](https://packagist.org/packages/sparclex/nova-import-card)
[![Total Downloads](https://poser.pugx.org/sparclex/nova-import-card/downloads)](https://packagist.org/packages/sparclex/nova-import-card)
[![Latest Unstable Version](https://poser.pugx.org/sparclex/nova-import-card/v/unstable)](https://packagist.org/packages/sparclex/nova-import-card)
[![License](https://poser.pugx.org/sparclex/nova-import-card/license)](https://packagist.org/packages/sparclex/nova-import-card)
[![StyleCI](https://github.styleci.io/repos/149668592/shield?branch=master)](https://github.styleci.io/repos/149668592)
[![TravisCi](https://travis-ci.org/Sparclex/nova-import-card.svg?branch=master)](https://travis-ci.org/Sparclex/nova-import-card)
[![codecov](https://codecov.io/gh/Sparclex/nova-import-card/branch/master/graph/badge.svg)](https://codecov.io/gh/Sparclex/nova-import-card)


A customizable import card for laravel nova. This package is more or less just a UI for [laravel-excel](https://laravel-excel.maatwebsite.nl) import. It is however **not** an official package from Maatwebsite.

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
        new \Sparclex\NovaImportCard\NovaImportCard(\App\Nova\User::class),
    ];
}
```

## Customization 

To customize the import process create a new importer class. The importer class is basically just an [import implementation of the laravel-excel package](https://laravel-excel.maatwebsite.nl/3.1/imports/). The easiest way to get started is to extend `Sparclex\NovaImportCard\BasicImporter` and overwrite the different methods. During the import process you may throw an exception of the type `Sparclex\NovaImportCard\ImportException` with an error message visible for the user. You may also add a `message(): String` method to customize the success message. 


The custom importer class can be registered on global or resource basis.

```php
// app/Nova/User.php

class User extends Resource
{

    public static $importer = CustomImporter::class;
    
    // ...
}

// or app/config/nova-import-card.php

return [
    'importer' => CustomImporter::class,
    
    // ...
]
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
