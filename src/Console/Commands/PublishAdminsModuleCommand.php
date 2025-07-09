<?php

namespace admin\admins\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishAdminsModuleCommand extends Command
{
    protected $signature = 'admins:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Admins module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Admins module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Admins');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'admin',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Admins module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/admins/src
        
        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/AdminManagerController.php' => base_path('Modules/Admins/app/Http/Controllers/Admin/AdminManagerController.php'),
            
            // Mail
            $basePath . '/Mail/WelcomeAdminMail.php' => base_path('Modules/Admins/app/Mail/WelcomeAdminMail.php'),
            
            // Requests
            $basePath . '/Requests/AdminCreateRequest.php' => base_path('Modules/Admins/app/Http/Requests/AdminCreateRequest.php'),
            $basePath . '/Requests/AdminUpdateRequest.php' => base_path('Modules/Admins/app/Http/Requests/AdminUpdateRequest.php'),
            
            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/Admins/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

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
            $content = str_replace('use admin\\admins\\Mail\\WelcomeAdminMail;', 'use Modules\\Admins\\app\\Mail\\WelcomeAdminMail;', $content);
            $content = str_replace('use admin\\admins\\Requests\\AdminCreateRequest;', 'use Modules\\Admins\\app\\Http\\Requests\\AdminCreateRequest;', $content);
            $content = str_replace('use admin\\admins\\Requests\\AdminUpdateRequest;', 'use Modules\\Admins\\app\\Http\\Requests\\AdminUpdateRequest;', $content);
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Admins\\'])) {
            $composer['autoload']['psr-4']['Modules\\Admins\\'] = 'Modules/Admins/app/';
            
            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
