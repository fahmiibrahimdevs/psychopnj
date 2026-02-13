<?php

namespace App\Traits;

trait WithPermissionCache
{
    public $userPermissions = [];

    /**
     * Cache user permissions to avoid N+1 queries in blade @can directives
     * Call this in mount() method
     */
    public function cacheUserPermissions()
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();
        $sessionKey = 'cached_permissions_' . $user->id;
        
        // Check if already cached in session
        if (session()->has($sessionKey)) {
            $this->userPermissions = session($sessionKey);
            return;
        }

        // Get permission names directly without loading Permission models
        // Get direct permissions
        $directPermissions = \DB::table('model_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->where('model_has_permissions.model_type', get_class($user))
            ->where('model_has_permissions.model_id', $user->id)
            ->pluck('permissions.name')
            ->toArray();
        
        // Get role permissions
        $rolePermissions = \DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->where('model_has_roles.model_type', get_class($user))
            ->where('model_has_roles.model_id', $user->id)
            ->pluck('permissions.name')
            ->toArray();
        
        // Merge and remove duplicates
        $this->userPermissions = array_unique(array_merge($directPermissions, $rolePermissions));
        
        // Store in session (cache for 60 minutes)
        session()->put($sessionKey, $this->userPermissions);
    }

    /**
     * Check if user has permission (cached version)
     * Use in blade: @if($this->can('permission.name'))
     */
    public function can($permission)
    {
        // Super admin always has access
        if (auth()->check() && auth()->user()->hasRole('super_admin')) {
            return true;
        }

        return in_array($permission, $this->userPermissions);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function canAny(array $permissions)
    {
        if (auth()->check() && auth()->user()->hasRole('super_admin')) {
            return true;
        }

        foreach ($permissions as $permission) {
            if (in_array($permission, $this->userPermissions)) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Clear cached permissions (call after role/permission changes)
     */
    public function clearPermissionCache()
    {
        if (auth()->check()) {
            $sessionKey = 'cached_permissions_' . auth()->id();
            session()->forget($sessionKey);
        }
    }
}
