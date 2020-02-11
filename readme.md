# ProtectionBanner

[![Build Status](https://travis-ci.org/Markohs/ProtectionBanner.svg?branch=master)](https://travis-ci.org/Markohs/ProtectionBanner)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![StyleCI][ico-styleci]][link-styleci]
[![](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)
[![](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://opensource.org/licenses/MIT)

Customizable Laravel Middleware to comply with EU Cookie Law (GDPR) and also protect adult websites from child access.

This software goes the hard way to be sure you comply the law:
 - All HTTP requests are captured and a full page banner is shown to the user. This page doesn't use cookies at all, no session is mantained.
 - Once the user accepts the condition, a cookie is sent, and user is redirected to the original intended page. All future interactions, proceed as usual. Original GET parameters are passed on to the original request.
 - It detects crawlers so SEO , opengraph, reddit, twitter... remains unaffected. They are unaffected by this package.
 - There is a whitelist mechanism so you can exclude certain URL from your website from this behaviour too.

Take a look at [contributing.md](contributing.md) to see a to do list.

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

### Cawler and SEO

This Middleware *will disable itself if it detects the agent is any crawler or redditbot*, so SEO, Google, and reddit/twitter/opengraph fetches remain unaffected.

This is the cause of the `jaybizzle/laravel-crawler-detect` dependency. I planned to use GEOIP too at some point to be able to disable it on countries where the cookie law might not be important to comply but this is not implemented yet.

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

I don't know if it's of any legal value, but it's possible to log the IP of all accepts of conditions. In `config/protectionbanner.php`:
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

If you are using Cloudflare or some kind of proxy to serve your website, you need to make sure you configure TrustedProxy correctly *or this Middleware might not work correctly*.

Make sure you keep the config file `/config/trustedproxy.php` up to date, or `app\Http\Middleware\TrustProxies.php` , variable `$proxies`. 

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
