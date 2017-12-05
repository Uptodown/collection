# Collection

A base collection class for managing group of items of the same class

[![Packagist](https://img.shields.io/packagist/dt/uptodown/collection.svg?style=flat-square)](https://packagist.org/packages/uptodown/random-username-generator) [![MIT License](https://img.shields.io/badge/license-MIT-007EC7.svg?style=flat-square)](http://opensource.org/licenses/MIT)

## Installation

To install it with composer:
```
composer require uptodown/collection
```

## Simple usage

```php
use Uptodown\Collection\AbstractCollection;

class ItemCollection extends AbstractCollection
{
    const CLASSNAME = Item::class;
}
```
