<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share permissions from session with all views
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $sessionKey = 'cached_permissions_' . $user->id;
                
                // If not in session, load permissions
                if (!session()->has($sessionKey)) {
                    $directPermissions = \DB::table('model_has_permissions')
                        ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                        ->where('model_has_permissions.model_type', get_class($user))
                        ->where('model_has_permissions.model_id', $user->id)
                        ->pluck('permissions.name')
                        ->toArray();
                    
                    $rolePermissions = \DB::table('model_has_roles')
                        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                        ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                        ->where('model_has_roles.model_type', get_class($user))
                        ->where('model_has_roles.model_id', $user->id)
                        ->pluck('permissions.name')
                        ->toArray();
                    
                    $userPermissions = array_unique(array_merge($directPermissions, $rolePermissions));
                    session()->put($sessionKey, $userPermissions);
                } else {
                    $userPermissions = session($sessionKey);
                }
                
                $isSuperAdmin = $user->hasRole('super_admin');
                
                $view->with('isSuperAdmin', $isSuperAdmin);
                $view->with('userPermissions', $userPermissions);
            } else {
                $view->with('isSuperAdmin', false);
                $view->with('userPermissions', []);
            }
        });
    }
}

