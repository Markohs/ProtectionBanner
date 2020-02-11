# ProtectionBanner

[![Build Status](https://travis-ci.org/Markohs/ProtectionBanner.svg?branch=master)](https://travis-ci.org/Markohs/ProtectionBanner)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![StyleCI][ico-styleci]][link-styleci]
[![](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)
[![](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://opensource.org/licenses/MIT)

Customizable Laravel Middleware to comply with EU cookie lawls and protect adult websites from child access forcing the client to accept conditions before any cookie or anything is sent, detects crawlers so SEO remains unaffected. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require markohs/protectionbanner
```

Publish the default config file and default view:
```
$  php artisan vendor:publish --tag=ProtectionBanner
```

You can now edit default settings in `config/protectionbanner.php` and use `/resources/views/vendor/protectionbanner/banner.blade.php` as a boilerplate for your banner.

## Usage

You can use any of the following methods to force the banner:

You can either force HTTPS in a single route in for example `routes/web.php`:
```php
Route::get('/','StaticPageController@getRoot')->middleware('protectionbanner');

```

You can also use the automatic MiddlewareGroup register mechanism in `config/protectionbanner.php`:
```php
	'autoregister' => ['web']
```

Or you can add the Middleware manually as usual in `app/Http/Kernel.php` in the MiddlewareGroups you require:

```php
...
'web' => [
    \App\Http\Middleware\EncryptCookies::class,
...
    \Markohs\ProtectionBanner\Middleware\ProtectionBannerMiddleware::class,
...

    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
..
```

### Set active environments

This package will only be active in the environments you specify, by default `stage`, `prod` and `production`, update `config/protectionbanner.php` if necessary:

```php
    'enabled_environments' => ['stage', 'prod', 'production'],
```

### URL whitelist mechanism

This package also has a path exclusion mechanism I found useful in my projects. Even if a request is affected by this Middleware, a list of paths is checked, in a "whitelist" spirit, those URLS won't trigger the banner.

You can set this url whitelist in  `config/protectionbanner.php`:
```php
    'whitelist' => [
        'example/url',
        'example2'
    ],

```

### Logging

I don't know if it's of any legal value, but it's possible to log the IP of all accepts of confitions, along ofc, with the timestamp `config/protectionbanner.php`:
```php
	/*
	 * Channel to log accept info, if necessary
	 * Default: null
	 * example: "accepts"
	 */
	'logchannel' => "accepts"
```

You will of course need to add that channel to `config/logging.php`.

## Important notes

If you are using Cloudflare or some kind of proxy to serve your website, you need to make sure you configure TrustedProxy correctly *or this Middleware will cause redirect loops*.

Make sure you keep `app\Http\Middleware\TrustProxies.php` , variable `$proxies`, up to date. Or the config file `/config/trustedproxy.php`

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email marcos@tyrellcorporation.es instead of using the issue tracker.

## Credits

- [Markohs][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/markohs/protectionbanner.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/markohs/protectionbanner.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/markohs/protectionbanner/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/238480865/shield

[link-packagist]: https://packagist.org/packages/markohs/protectionbanner
[link-downloads]: https://packagist.org/packages/markohs/protectionbanner
[link-styleci]: https://styleci.io/repos/238480865
[link-author]: https://github.com/markohs
[link-contributors]: ../../contributors
