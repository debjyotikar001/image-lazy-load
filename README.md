# Media Lazy Load for Laravel

A Laravel package to help users implement media lazy loading using PHP and JavaScript, optimizing page load speeds by only loading media when they are in view. It runs automatically when you load a view or page. Increase your website performance on page load and save bandwidth.

## Key Features:

1. Media (images, iframes, videos and audios) lazy loading for faster loading. [Read More...](#enable)
2. Supports excluding specific routes urls paths from being media lazy loading. [Read More...](#skip-or-ignore-specific-routes-urls)
3. Configurable to skip lazy loading for specific application Environment. [Read More...](#allowed-environments)
4. Easy integration with Laravel's middleware system. [Read More...](#register-the-middleware)
5. Supports lazy loading of background images set via inline CSS `background-image:url(...)`.
6. Customizable root margin allows you to control when elements start loading relative to their position in the viewport. [Read More...](#root-margin)
7. Adjustable threshold defines how much of an element must be visible before it loads. [Read More...](#threshold)

## Installation

Media Lazy Load for Laravel requires PHP 7.4 or higher. This particular version supports Laravel 8.x, 9.x, 10.x, and 11.x.

To get the latest version, simply require the project using [Composer](https://getcomposer.org):

```sh
composer require debjyotikar001/media-lazy-load
```

## Configuration

Media Lazy Load for Laravel supports optional configuration. To get started, you'll need to publish all vendor assets:

```sh
php artisan vendor:publish --provider="Debjyotikar001\MediaLazyLoad\MediaLazyLoadServiceProvider"
```

This will create a `config/medialazyload.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package.

## Register the Middleware
In order to add media lazy loading functionality in Laravel, you need to add the MedLazyLoad middleware.

### (Laravel 10 or older)
In `app/Http/Kernel.php` file:

```php
protected $middleware = [
  // other middleware
  \Debjyotikar001\MediaLazyLoad\Middleware\MedLazyLoad::class,
];
```
or 
```php
protected $middleware = [
  // other middleware
  'medialazyload',
];
```
You can use the middleware class or middleware alias `medialazyload` in web middleware or route.

### (Laravel 11 or newer)
In `bootstrap/app.php` file:

```php
->withMiddleware(function (Middleware $middleware) {
  $middleware->web(append: [
    // other middleware
    \Debjyotikar001\MediaLazyLoad\Middleware\MedLazyLoad::class,
  ]);
})
```
or 
```php
->withMiddleware(function (Middleware $middleware) {
  $middleware->web(append: [
    // other middleware
    'medialazyload',
  ]);
})
```
You can use the middleware class or middleware alias `medialazyload` in web middleware or route.

## Usage
This is how you can use MediaLazyLoad for Laravel in your project.

### Enable
You must set `true` on `enabled` in the `config/medialazyload.php` file enable media lazy loading functionality. For example:

```php
'enabled' => env('MEDLAZYLOAD_ENABLED', true),
```

### Jquery for Lazy Loading
If you want to use Jquery to handle lazy loading, then set `true` on `jquery` and you can specify its CDN URL on `jqueryUrl` in the `config/medialazyload.php` file. For example:

```php
'jquery' => env('MEDLAZYLOAD_JQUERY', false),
'jqueryUrl' => env('MEDLAZYLOAD_JQUERY_URL', 'https://code.jquery.com/jquery-3.7.1.min.js'),
```

### Allowed Environments
If you want to disable it in specific environments such as during local development or testing to simplify debugging. Then set environments values in a comma (`,`) separated string in the `config/medialazyload.php` file, default `local,production,staging`. For example:

```php
'allowed_envs' => env('MEDLAZYLOAD_ALLOWED_ENVS', 'local,production,staging'),
```

### Skip or Ignore specific Routes Urls
If you want to skip or ignore specific routes urls, then you have to set paths in the `config/medialazyload.php` file. You can use '*' as wildcard. For example:

```php
'skip_urls' => [
    '/',
    'about',
    'user/*',
    '*_dashboard',
    '*/download/*',
  ],
```
#### Example URLs:
- `/`: Home URL will be excluded from minification.
- `about`: This exact URL will be excluded from minification.
- `user/*`: Any URL starting with `user/` (like `user/profile`, `user/settings`) will be excluded.
- `*_dashboard`: Any URL ending with `_dashboard` (like `admin_dashboard`, `user_dashboard`) will be excluded.
- `*/download/*`: Any URL has `download` (like `pdf/download/001`, `image/download/debjyotikar001`) will be excluded.

### Root Margin
This option allows you to control how early or late the elements should be lazy-loaded relative to their position in the viewport. You can set values in the `config/medialazyload.php` file, default `'0px 0px 100px 0px'`. Format: `'top right bottom left'`. For example:

```php
'rootMargin' => env('MEDLAZYLOAD_ROOTMARGIN', '0px 0px 100px 0px'),
```
Here `'0px 0px 100px 0px'` means that elements will start loading 100px before they enter the viewport from the bottom. You can also use percentages (e.g., `'10% 0px 0px 0px'`).

### Threshold
This option allows you to control how much of an element must be visible in the viewport before it starts loading. It accepts a value between `0` and `1`. You can set values in the `config/medialazyload.php` file, default `0.1`. For example:

```php
'threshold' => env('MEDLAZYLOAD_THRESHOLD', 0.1),
```
Here `0.1` means the element will start loading when 10% of it is visible in the viewport.

#### Example values:
- `0` means elements will start loading as soon as one pixel is visible.
- `1` means the element will only load when it's fully visible in the viewport.
- `0.05` means that 5% of the element needs to be visible before loading starts.
- `0.1` means that 10% of the element needs to be visible before loading starts.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING](CONTRIBUTING.md) for details on how to get started.

## License

Media Lazy Load is licensed under the [MIT license](LICENSE).

## Support

If you are having general issues with this package, feel free to contact us on [debjyotikar001@gmail.com](mailto:debjyotikar001@gmail.com)
