<?php

declare(strict_types=1);

namespace Joy\Core;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class CoreServiceProvider
 *
 * @category  Package
 * @package   JoyCore
 * @author    Ramakant Gangwar <gangwar.ramakant@gmail.com>
 * @copyright 2021 Copyright (c) Ramakant Gangwar (https://github.com/rxcod9)
 * @license   http://github.com/rxcod9/joy-core/blob/main/LICENSE New BSD License
 * @link      https://github.com/rxcod9/joy-core
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        $this->bootBlueprintMacros();

        $this->registerPublishables();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'joy-core');

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'joy-core');
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/web.php');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix(config('joy-core.route_prefix', 'api'))
            ->middleware('api')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/core.php', 'joy-core');

        $this->registerCommands();
    }

    /**
     * Boot blueprint macros.
     *
     * @return void
     */
    protected function bootBlueprintMacros(): void
    {
        Blueprint::macro('createdModifiedBy', function () {
            $this->unsignedBigInteger('modified_by_id')->nullable();
            $this->unsignedBigInteger('created_by_id')->nullable();

            $this->foreign('created_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $this->foreign('modified_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });

        // Uuids
        Blueprint::macro('createdModifiedByUuids', function () {
            $this->uuid('modified_by_id')->nullable();
            $this->uuid('created_by_id')->nullable();
        });

        // Assigned + CreatedBy + ModifiedBy + Timestamps + SoftDeletes [BigInt]
        Blueprint::macro('acmts', function () {
            $this->unsignedBigInteger('assigned_to_id')->nullable();
            $this->createdModifiedBy();
            $this->timestamps();
            $this->softDeletes();

            $this->foreign('assigned_to_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });

        // Assigned + CreatedBy + ModifiedBy + Timestamps + SoftDeletes [Uuids]
        Blueprint::macro('acmtsUuids', function () {
            $this->uuid('assigned_to_id')->nullable();
            $this->createdModifiedByUuids();
            $this->timestamps();
            $this->softDeletes();
        });
    }

    /**
     * Register publishables.
     *
     * @return void
     */
    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__ . '/../config/core.php' => config_path('joy-core.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/joy-core'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/joy-core'),
        ], 'translations');
    }

    protected function registerCommands(): void
    {
        //
    }
}
