# Stemmer Bundle for Symfony

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dompat/stemmer-bundle.svg?style=flat-square)](https://packagist.org/packages/dompat/stemmer-bundle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.3-8892bf.svg?style=flat-square)](https://php.net)
[![Symfony Version](https://img.shields.io/badge/symfony-%5E6.4%20%7C%20%5E7.0%20%7C%20%5E8.0-000000.svg?style=flat-square)](https://symfony.com)

This bundle integrates the [dompat/stemmer](https://github.com/dompat/stemmer) library into Symfony. It provides automatic service registration, Twig filters and easy configuration for language drivers.

## ✨ Features

- **Automatic Driver Registration:** Registers all drivers provided by [dompat/drivers](https://github.com/dompat/stemmer).
- **Twig Support:** Simple `|stem` filter for your templates.
- **Configurable Contexts:** Map custom locales to specific drivers via YAML.
- **Autoconfiguration:** Just implement `DriverInterface` to add your own drivers.
- **Driver Priority:** Easily override core drivers with your own implementation.

## 🚀 Installation

Install the bundle via [Composer](https://getcomposer.org/):

```bash
composer require dompat/stemmer-bundle
```

Enable the bundle in `config/bundles.php`:

```php
return [
    // ...
    Dompat\StemmerBundle\DompatStemmerBundle::class => ['all' => true],
];
```

## 📖 Usage

### Using the Service

You can inject `Dompat\Stemmer\Stemmer` into your services or controllers:

```php
use Dompat\Stemmer\Stemmer;
use Dompat\Stemmer\Enum\StemmerMode;

public function search(string $query, Stemmer $stemmer)
{
    $stemmed = $stemmer->stem($query, 'cs', StemmerMode::AGGRESSIVE);
    // ...
}
```

### Using Twig

The bundle provides a `stem` filter:

```twig
{# Simple usage (uses LIGHT mode by default) #}
{{ 'working'|stem('en') }} {# output: work #}

{# With explicit mode #}
{{ 'declaration'|stem('en', 'aggressive') }} {# output: declar #}
```

Available modes: `light` (default), `aggressive`.

## ⚙️ Configuration

By default, the bundle registers all drivers found in [dompat/stemmer](https://github.com/dompat/stemmer). You can add custom mapping or override default drivers in `config/packages/dompat_stemmer.yaml`:

```yaml
dompat_stemmer:
    contexts:
        sk: Dompat\Stemmer\Driver\CzechDriver  # Use Czech rules for Slovak language
        en: App\Stemmer\MyCustomEnglishDriver  # Force your custom driver for English
```

## 🌍 Adding Custom Drivers

To add a new language driver, implement `Dompat\Stemmer\Contract\DriverInterface`. 

### Autowiring and Priority

If you use autoconfiguration (default in Symfony), your driver will be automatically registered with the `Stemmer` manager. 

Custom drivers have a higher priority by default. This means if you create your own `EnglishDriver`, it will automatically replace the original one from the library without any extra configuration.

If you want to use a specific driver for a locale (e.g., to switch back to the original one or map a different class), use the `contexts` configuration shown above.

```php
namespace App\Stemmer;

use Dompat\Stemmer\Contract\DriverInterface;
use Dompat\Stemmer\Contract\StemmerModeInterface;

class FrenchDriver implements DriverInterface
{
    public function getLocale(): string
    {
        return 'fr';
    }

    public function stem(string $word, StemmerModeInterface $mode): string
    {
        // ... your implementation
    }

    public function __toString(): string
    {
        return 'FrenchDriver';
    }
}
```

## 📄 License

This bundle is licensed under the [MIT License](LICENSE).
