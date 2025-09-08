<?php

namespace admin\admins;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Admins/resources/views'), // Published module views first
            resource_path('views/admin/admin'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'admin');
        
        $this->mergeConfigFrom(__DIR__.'/../config/admin.php', 'admin.constants');

        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Admins/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Admins/resources/views'), 'admins-module');
        }

        // Only publish automatically during package installation, not on every request
        // Use 'php artisan admins:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();
        
        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('Modules/Admins/resources/views/'),
        ], 'admin');
       
        $this->registerAdminRoutes();

    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();
            
        $slug = $admin->website_slug ?? 'admin';

        $routeFile = base_path('Modules/Admins/routes/web.php');
        if (!file_exists($routeFile)) {
            $routeFile = __DIR__ . '/routes/web.php'; // fallback to package route
        }

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group($routeFile);
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\admins\Console\Commands\PublishAdminsModuleCommand::class,
                \admin\admins\Console\Commands\CheckModuleStatusCommand::class,
                \admin\admins\Console\Commands\DebugAdminsCommand::class,
                \admin\admins\Console\Commands\TestViewResolutionCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/AdminManagerController.php' => base_path('Modules/Admins/app/Http/Controllers/Admin/AdminManagerController.php'),
            
            // Mail
            __DIR__ . '/../src/Mail/WelcomeAdminMail.php' => base_path('Modules/Admins/app/Mail/WelcomeAdminMail.php'),
            
            // Requests
            __DIR__ . '/../src/Requests/AdminCreateRequest.php' => base_path('Modules/Admins/app/Http/Requests/AdminCreateRequest.php'),
            __DIR__ . '/../src/Requests/AdminUpdateRequest.php' => base_path('Modules/Admins/app/Http/Requests/AdminUpdateRequest.php'),
            
            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/Admins/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));
                
                // Read the source file
                $content = File::get($source);
                
                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);
                
                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\admins\\Controllers;' => 'namespace Modules\\Admins\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\admins\\Mail;' => 'namespace Modules\\Admins\\app\\Mail;',
            'namespace admin\\admins\\Requests;' => 'namespace Modules\\Admins\\app\\Http\\Requests;',
            
            // Use statements transformations
            'use admin\\admins\\Controllers\\' => 'use Modules\\Admins\\app\\Http\\Controllers\\Admin\\',
            'use admin\\admins\\Mail\\' => 'use Modules\\Admins\\app\\Mail\\',
            'use admin\\admins\\Requests\\' => 'use Modules\\Admins\\app\\Http\\Requests\\',
            
            // Class references in routes
            'admin\\admins\\Controllers\\AdminManagerController' => 'Modules\\Admins\\app\\Http\\Controllers\\Admin\\AdminManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Mail')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for mail and requests
        $content = str_replace(
            'use admin\\admins\\Mail\\WelcomeAdminMail;',
            'use Modules\\Admins\\app\\Mail\\WelcomeAdminMail;',
            $content
        );
        
        $content = str_replace(
            'use admin\\admins\\Requests\\AdminCreateRequest;',
            'use Modules\\Admins\\app\\Http\\Requests\\AdminCreateRequest;',
            $content
        );

        $content = str_replace(
            'use admin\\admins\\Requests\\AdminUpdateRequest;',
            'use Modules\\Admins\\app\\Http\\Requests\\AdminUpdateRequest;',
            $content
        );
        $content = str_replace(
            'use admin\\admin_auth\\Models\\Admin;',
            'use Modules\\Admins\\app\\Http\\Models\\Admin;',
            $content
        );
        $content = str_replace(
            'use admin\\admin_role_permissions\\Models\\Role;',
            'use Modules\\AdminRolePermissions\\app\\Http\\Models\\Role;',
            $content
        );

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\admins\\Controllers\\AdminManagerController',
            'Modules\\Admins\\app\\Http\\Controllers\\Admin\\AdminManagerController',
            $content
        );

        return $content;
    }
}