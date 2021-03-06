# A simple profile page for Dasher

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zvive/dasher.svg?style=flat-square)](https://packagist.org/packages/zvive/dasher)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/zvive/dasher/run-tests?label=tests)](https://github.com/zvive/dasher/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/zvive/dasher/Check%20&%20fix%20styling?label=code%20style)](https://github.com/zvive/dasher/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/zvive/dasher.svg?style=flat-square)](https://packagist.org/packages/zvive/dasher)

This package provides a very simple `Profile` page that allows the current user to manage their name, email address and password inside of Dasher.

![Screenshot of Page](./art/screenshot.png)

## Installation

You can install the package via Composer:

```bash
composer require dasher/profile
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="dasher-profile-views"
```

## Usage

This package will automatically register the `Profile` page as a Livewire component, but won't automatically add it to Dasher. You should add the following line of code to your `AppServiceProvider::register()` method.

```php
Dasher::registerPages([
    \Dasher\Profile\Pages\Profile::class
]);
```

If you visit your Dasher panel now, you'll see a new `Account` navigation group as well as a `Profile` page.

## Customising the `Profile` page

Since the package **does not** automatically add the `Profile` page to your Dasher panel, you are free to extend the page and customise it yourself.

You should first run the following command in your terminal:

```bash
php artisan dasher:page Profile
```

This will create a new `App\Dasher\Pages\Profile` class in your project.

You can then update this class to extend the `Dasher\Profile\Pages\Profile` class.

```php
namespace App\Dasher\Pages;

use Dasher\Profile\Pages\Profile as BaseProfile;

class Profile extends BaseProfile
{
    // ...
}
```

Dasher will automatically register your new `Profile` page and you're able to customise it to your liking. You can remove the navigation group, modify the form, etc.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](../../.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Patrick Curl](https://github.com/patrickcurl)
- [Ryan Chandler](https://github.com/ryangjchandler)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
