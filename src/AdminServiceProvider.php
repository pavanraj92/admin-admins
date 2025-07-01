<?php

namespace admin\admins;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package
        // $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->registerAdminRoutes();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/admin.php', 'admin.constants');
        

        $this->publishes([  
            __DIR__.'/../resources/views' => resource_path('views/admin/admin'),
            __DIR__ . '/../config/admin.php' => config_path('constants/admin/admin.php'),
            __DIR__ . '/../src/Controllers' => app_path('Http/Controllers/Admin/AdminManager'),
            __DIR__ . '/../src/Models' => app_path('Models/Admin/Admin'),
            __DIR__ . '/routes/web.php' => base_path('routes/admin/admin_admin.php'),
        ], 'admin');


    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $slug = DB::table('admins')->latest()->value('website_slug') ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            });
    }

    public function register()
    {
        // You can bind classes or configs here
    }
}
