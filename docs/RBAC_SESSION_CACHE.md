# RBAC Session Cache Implementation

## Problem Yang Diselesaikan

### 1. Query Duplicate (Sebelum)
Setiap page load menjalankan **4 queries permission** yang sama:
- 2x dari WithPermissionCache (di component)
- 2x dari PermissionComposer (di layout)

```sql
-- Query 1 & 3 (duplicate):
SELECT permissions.name FROM model_has_permissions 
INNER JOIN permissions ON permissions.id = model_has_permissions.permission_id 
WHERE model_has_permissions.model_type = 'App\Models\User' 
AND model_has_permissions.model_id = 11

-- Query 2 & 4 (duplicate):
SELECT permissions.name FROM model_has_roles 
INNER JOIN roles ON roles.id = model_has_roles.role_id 
INNER JOIN role_has_permissions ON role_has_permissions.role_id = roles.id 
INNER JOIN permissions ON permissions.id = role_has_permissions.permission_id 
WHERE model_has_roles.model_type = 'App\Models\User' 
AND model_has_roles.model_id = 11
```

### 2. Column `roles.display_name` Not Found
Query di ControlUser.php menggunakan `roles.display_name` padahal table roles hanya punya:
- id
- name
- guard_name
- created_at
- updated_at

## Solusi Implementasi

### 1. Session Cache di WithPermissionCache.php
```php
public function cacheUserPermissions()
{
    if (!auth()->check()) return;
    
    $user = auth()->user();
    $sessionKey = 'cached_permissions_' . $user->id;
    
    // Check if already cached in session
    if (session()->has($sessionKey)) {
        $this->userPermissions = session($sessionKey);
        return;
    }
    
    // Load permissions (ONLY ONCE per session)
    $directPermissions = DB::table('model_has_permissions')...
    $rolePermissions = DB::table('model_has_roles')...
    
    $this->userPermissions = array_unique(array_merge($directPermissions, $rolePermissions));
    
    // Store in session
    session()->put($sessionKey, $this->userPermissions);
}
```

### 2. AppServiceProvider View Composer
Mengganti PermissionComposer dengan View Composer sederhana yang:
- Menggunakan session yang sama dengan WithPermissionCache
- Load permission HANYA jika belum ada di session
- Share `$isSuperAdmin` dan `$userPermissions` ke semua views

```php
View::composer('*', function ($view) {
    if (auth()->check()) {
        $user = auth()->user();
        $sessionKey = 'cached_permissions_' . $user->id;
        
        // If not in session, load permissions
        if (!session()->has($sessionKey)) {
            // Load permissions...
            session()->put($sessionKey, $userPermissions);
        } else {
            $userPermissions = session($sessionKey);
        }
        
        $view->with('isSuperAdmin', $user->hasRole('super_admin'));
        $view->with('userPermissions', $userPermissions);
    }
});
```

### 3. Fix ControlUser Query
Ganti `roles.display_name` → `roles.name`:

```php
DB::raw('GROUP_CONCAT(roles.name SEPARATOR ", ") as roles_names')
```

## Hasil Optimasi

### Sebelum:
- **4 permission queries** per page load
- Error: Column `roles.display_name` not found
- Query duplicate antara component dan layout

### Sesudah:
- **2 permission queries ONLY on first page load** (kemudian pakai session)
- **0 permission queries on subsequent page loads** dalam session yang sama
- Tidak ada query duplicate
- Column error fixed

## Query Flow

### First Page Load (Login):
1. User login
2. AppServiceProvider View Composer check session → **NOT FOUND**
3. Load permissions (2 queries), simpan ke session
4. Component mount() call `cacheUserPermissions()` → check session → **FOUND**
5. Ambil dari session (NO QUERY)

### Subsequent Page Loads:
1. AppServiceProvider check session → **FOUND** → ambil dari session (NO QUERY)
2. Component mount() check session → **FOUND** → ambil dari session (NO QUERY)
3. **Total: 0 permission queries**

## Cache Invalidation

Ketika role/permission user berubah, panggil:

```php
$this->clearPermissionCache();
```

Ini akan menghapus session cache, sehingga page load berikutnya akan reload permissions.

## Files Modified

1. ✅ `app/Traits/WithPermissionCache.php` - Added session caching
2. ✅ `app/Providers/AppServiceProvider.php` - Replaced PermissionComposer with View Composer
3. ✅ `app/Livewire/Organisasi/ControlUser.php` - Fixed `roles.display_name` → `roles.name`
4. ✅ `app/Http/View/Composers/PermissionComposer.php` - **DELETED** (no longer needed)

## Testing

Setelah implementasi:
1. Clear cache: `php artisan view:clear && php artisan cache:clear`
2. Login ke aplikasi
3. Buka Laravel Debugbar → Database tab
4. Check query count:
   - First load: 2 permission queries
   - Subsequent loads: 0 permission queries
5. Verify no more "Query Duplicate" di debugbar

## Maintenance Notes

- Session cache akan hilang ketika user logout atau session expired
- Jika ada perubahan role/permission di PermissionMatrix, pastikan clear session cache user yang affected
- Super admin tetap bypass permission checks dengan `hasRole('super_admin')`
