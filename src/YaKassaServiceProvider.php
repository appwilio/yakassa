<?php

/**
 * This file is part of Ya.Kassa package.
 *
 * © Appwilio (http://appwilio.com)
 * © JhaoDa ()
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Appwilio\YaKassa;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class YaKassaServiceProvider extends ServiceProvider
{
    /** @var Application */
    protected $app;

    protected $defer = true;

    public function register(): void
    {
        $this->app->bind('appwilio.yakassa', function ($app) {
            $config = $app['config']['services.yakassa'];

            $yaKassa = new YaKassa(
                $config['shop_id'], $config['showcase_id'], $config['shop_password'], $config['test_mode']
            );

            return $yaKassa->setRequest($app['appwilio.yakassa.request']);
        });
        $this->app->alias('appwilio.yakassa', YaKassa::class);

        $this->app->bind('appwilio.yakassa.request', function ($app) {
            return new YaKassaRequest($app['request']->request->all());
        });
        $this->app->alias('appwilio.yakassa.request', YaKassaRequest::class);
    }

    public function provides(): array
    {
        return [
            'appwilio.yakassa', YaKassa::class,
            'appwilio.yakassa.request', YaKassaRequest::class
        ];
    }
}
