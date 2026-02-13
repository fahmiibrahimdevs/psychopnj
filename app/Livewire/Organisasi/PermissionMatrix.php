<?php

namespace App\Livewire\Organisasi;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Title;
use App\Traits\WithPermissionCache;
use Illuminate\Support\Facades\DB;

class PermissionMatrix extends Component
{
    use WithPermissionCache;
    #[Title('Matrix Role & Permission')]

    public $roles;
    public $search = '';
    public $matrix = []; 

    public function mount()
    {
        // Cache user permissions to avoid N+1 queries
        $this->cacheUserPermissions();
        
        // Eager load permissions to prevent N+1 queries in the view
        $this->roles = Role::with('permissions')->where('name', '!=', 'super_admin')->get();
    }

    public function togglePermission($roleId, $permissionId)
    {
        $role = Role::find($roleId);
        $permission = Permission::find($permissionId);

        if ($role && $permission) {
            DB::beginTransaction();
            try {
                if ($role->hasPermissionTo($permission->name)) {
                    $role->revokePermissionTo($permission);
                } else {
                    $role->givePermissionTo($permission);
                }
                
                // Clear permission cache for all users with this role
                // Note: Spatie permission cache is handled automatically for roles usually
                $this->clearAllUserPermissionCache();
                
                DB::commit();
                
                // Refresh roles to reflect changes in the view without full page reload
                $this->roles = Role::with('permissions')->where('name', '!=', 'super_admin')->get();
                
                $this->dispatch('saved'); 
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('swal:modal', [
                    'type'    => 'error',
                    'message' => 'Error!',
                    'text'    => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage(),
                ]);
            }
        }
    }
    
    /**
     * Clear permission cache for all users
     * Call this after changing role permissions
     */
    private function clearAllUserPermissionCache()
    {
        // Get all session keys that start with 'cached_permissions_'
        // Note: This is a simple approach. For production, consider using Redis with pattern matching
        // or store user IDs who have the affected role and clear their specific caches
        
        // For now, we'll clear the current user's cache
        // In a real scenario, you'd want to invalidate cache for all affected users
        if (auth()->check()) {
            $sessionKey = 'cached_permissions_' . auth()->id();
            session()->forget($sessionKey);
        }
    }

    public function render()
    {
        $query = Permission::orderBy('name');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $allPermissions = $query->get();
        $modules = [];

        foreach ($allPermissions as $perm) {
            $parts = explode('.', $perm->name);
            $moduleName = ucfirst(str_replace('_', ' ', $parts[0]));
            if (isset($parts[1])) {
                 // Clean up module names if needed
            }
            $modules[$moduleName][] = $perm;
        }

        return view('livewire.organisasi.permission-matrix', [
            'modules' => $modules
        ]);
    }
}
