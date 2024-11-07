<p align="center">
    <img src="/art/logo.webp" width="50%" alt="Laravel Pulse GraphQL Logo">
</p>
<p align="center">
    <a href="https://packagist.org/packages/larassn/laravel-pulse-graphql"><img src="https://img.shields.io/packagist/dt/larassn/laravel-pulse-graphql" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/larassn/laravel-pulse-graphql"><img src="https://img.shields.io/packagist/v/larassn/laravel-pulse-graphql" alt="Latest Stable Version"></a>
    <a href="./LICENSE" target="_blank"><img src="https://img.shields.io/packagist/l/larassn/laravel-pulse-graphql" alt="License"></a>
</p>

<a name="introduction"></a>

## Introduction

**Laravel Pulse GraphQL** is a powerful monitoring package designed for Laravel applications that use GraphQL. Built
specifically for applications utilizing the [rebing/graphql-laravel](https://github.com/rebing/graphql-laravel) package,
it integrates with Laravel Pulse to track and measure GraphQL request performance. This package provides developers with
valuable insights into request timing and response counts, helping to maintain and optimize the performance of GraphQL
endpoints in Laravel applications.

## Installation

Install the package via Composer:

```bash
composer require larassn/laravel-pulse-graphql
```

Next, you need to publish the Pulse configuration file. Run the following command:

```bash
php artisan vendor:publish --provider="Laravel\Pulse\PulseServiceProvider" --tag="config"
```

This will create a `pulse.php` configuration file in the `config` directory. For more details on configuring Pulse, you
can refer to
the [Pulse configuration](https://laravel.com/docs/master/pulse#configuration).

After publishing the configuration, add the `QueryRecorder` to the `recorders` array in the `pulse.php` configuration
file:

```php
return [
    // ...

    'recorders' => [
        // Other recorders...
        \LaraSsn\LaravelPulseGraphql\Recorder\QueryRecorder::class => [], 
    ]
];
```

Then, add the GraphQL card to the Pulse dashboard:

```html

<livewire:pulse.graphql cols="6"/>
```

For more information on customizing the Pulse dashboard, refer to
the [dashboard customization](https://laravel.com/docs/master/pulse#dashboard-customization).

## Usage

Once installed, **Laravel Pulse GraphQL** will automatically start recording GraphQL request data, which you can monitor
on the Pulse dashboard. You can customize its configuration in `pulse.php` to suit your application's specific needs.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.