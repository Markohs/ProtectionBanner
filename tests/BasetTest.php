<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class BaseTest extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDummyRoutes();
    }

    protected function getPackageProviders($app)
    {
        return ['Markohs\ProtectionBanner\ProtectionBannerServiceProvider',
                'Jaybizzle\LaravelCrawlerDetect\LaravelCrawlerDetectServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'ProtectionBanner' => \Markohs\ProtectionBanner\ProtectionBanner::class,
            'Crawler' => \Jaybizzle\LaravelCrawlerDetect\Facades\LaravelCrawlerDetect::class
        ];
    }

    protected function setUpDummyRoutes()
    {
        $this->app['router']->get('test', ['uses' => function () {
            return 'hello world';
        }]);

        $this->app['router']->get('test_forced', ['uses' => function () {
            return 'hello world';
        }])->middleware('Markohs\ProtectionBanner\Middleware\ProtectionBannerMiddleware');
    }
}
