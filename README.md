# A card which lets you batch import resources

[![Latest Stable Version](https://poser.pugx.org/sparclex/nova-import-card/v/stable)](https://packagist.org/packages/sparclex/nova-import-card)
[![Total Downloads](https://poser.pugx.org/sparclex/nova-import-card/downloads)](https://packagist.org/packages/sparclex/nova-import-card)
[![Latest Unstable Version](https://poser.pugx.org/sparclex/nova-import-card/v/unstable)](https://packagist.org/packages/sparclex/nova-import-card)
[![License](https://poser.pugx.org/sparclex/nova-import-card/license)](https://packagist.org/packages/sparclex/nova-import-card)
[![StyleCI](https://github.styleci.io/repos/149668592/shield?branch=master)](https://github.styleci.io/repos/149668592)
[![CircleCI](https://circleci.com/gh/Sparclex/nova-import-card.svg?style=svg)](https://circleci.com/gh/Sparclex/nova-import-card)

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

## Customization 

### File Reader

If you want to import a different file type than csv you can write your own file reader.

```php
class XmlFileReader extends ImportFileReader
{
    public static function mimes()
    {
        return 'xml,txt';
    }

    public function read(): array
    {
        $data = [];
        $handle = fopen($this->file->getRealPath(), 'r');
        // ...
        // store xml data in array
        // ...
        fclose($handle);
        return $data;
    }
}
```

And register the file type as default or on resource basis.
```php
// app/Nova/User.php

class User extends Resource
{

    public static $importFileReader = XmlFileReader::class;
    
    // ...
}

// or app/config/sparclex-nova-import-card.php

return [
    'file_reader' => XmlFileReader::class,
    
    // ...
]
```

### Import Handler

You can customize how the data import is handled by defining an custom import handler. The handle method runs inside a database transaction.

```php
class CustomImportHandler extends Sparclex\NovaImportCard\ImportHandler
{

    /**
     * Handles the data import
     *
     * @param $resource
     */
    public function handle($resource)
    {
        $data = $this->data;
        // ...
        // custom import handling
        // ...
    }
} 
```

and registering it as default or on resource basis.

```php
// app/Nova/User.php

class User extends Resource
{

    public static $importHandler = CustomImportHandler::class;
    
    // ...
}

// or app/config/sparclex-nova-import-card.php

return [
    'import_handler' => CustomImportHandler::class,
    
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

## Todo
- [ ] Add tests
- [ ] Add support for more File Types
- [ ] Show all error messages
- [ ] Chunk inserts
- [ ] Queable
