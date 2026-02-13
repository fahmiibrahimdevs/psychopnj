# RBAC Performance Issue & Fix

## 🐛 Problem Identified

**Symptoms:**

- Models queries: **918**
- Gate checks: **953**
- Page extremely slow and "bengkak"
- Happens after implementing RBAC with `@can()` directives

**Root Cause:**

```blade
@forelse ($data as $row)
    @can("permission.view")
        <!-- Query to DB -->
        <button>View</button>
    @endcan

    @can("permission.edit")
        <!-- Query to DB -->
        <button>Edit</button>
    @endcan

    @can("permission.delete")
        <!-- Query to DB -->
        <button>Delete</button>
    @endcan
@endforelse
```

**Impact:**

- 30 items × 4 `@can()` checks = **120 database queries PER PAGE**
- Each `@can()` triggers: Permission lookup + Role check + Gate evaluation
- Multiplied across multiple pages = **MASSIVE performance degradation**

---

## ✅ Solution Implemented

### 1. Created `WithPermissionCache` Trait

**File:** `app/Traits/WithPermissionCache.php`

```php
<?php

namespace App\Traits;

trait WithPermissionCache
{
    public $userPermissions = [];

    public function cacheUserPermissions()
    {
        if (auth()->check()) {
            // Get ALL user permissions ONCE
            $this->userPermissions = auth()->user()
                ->getAllPermissions()
                ->pluck('name')
                ->toArray();
        }
    }

    public function can($permission)
    {
        // Super admin bypass
        if (auth()->check() && auth()->user()->hasRole('super_admin')) {
            return true;
        }

        // Fast array lookup instead of DB query
        return in_array($permission, $this->userPermissions);
    }
}
```

**How it works:**

1. **Mount phase**: Load ALL user permissions into memory (1 query)
2. **View phase**: Use `in_array()` for permission checks (0 queries)
3. **Result**: **N+1 problem ELIMINATED**

---

### 2. Updated Livewire Components

**Files Updated:**

- ✅ `app/Livewire/Akademik/ProgramKegiatan.php`
- ✅ `app/Livewire/Akademik/Pertemuan.php`
- ✅ `app/Livewire/Akademik/Project.php`

**Changes:**

```php
use App\Traits\WithPermissionCache;

class ProgramKegiatan extends Component
{
    use WithPagination, WithFileUploads, ImageCompressor, WithPermissionCache;

    public function mount()
    {
        // Cache permissions ONCE
        $this->cacheUserPermissions();

        // ... rest of mount logic
    }
}
```

---

### 3. Updated Blade Views

**Change Pattern:**

**Before (Slow - N+1):**

```blade
@can("program_pembelajaran.edit")
    <button>Edit</button>
@endcan
```

**After (Fast - Cached):**

```blade
@if ($this->can("program_pembelajaran.edit"))
    <button>Edit</button>
@endif
```

**Files to Update:**

- ⏳ `resources/views/livewire/akademik/program-pembelajaran.blade.php` (Partial)
- ⏳ `resources/views/livewire/akademik/pertemuan.blade.php`
- ⏳ `resources/views/livewire/akademik/presensi-pertemuan.blade.php`
- ⏳ `resources/views/livewire/akademik/project.blade.php`
- ⏳ `resources/views/livewire/akademik/project-teams.blade.php`
- ⏳ `resources/views/livewire/akademik/bank-soal/soal-pertemuan.blade.php`
- ⏳ `resources/views/livewire/akademik/hasil-ujian-pertemuan.blade.php`
- ⏳ `resources/views/livewire/akademik/status-anggota-ujian.blade.php`
- ⏳ `resources/views/livewire/akademik/hasil-ujian/koreksi.blade.php`

---

## 📊 Expected Performance Improvement

### Before Fix:

```
Queries: 918
Gate checks: 953
Load time: 5-10 seconds
Memory: High
```

### After Fix:

```
Queries: ~20-30 (depends on page data)
Gate checks: 0 (cached in memory)
Load time: <1 second
Memory: Minimal increase (array in memory)
```

**Improvement: ~95% reduction in queries!**

---

## 🚀 Implementation Steps

### Step 1: Update ALL Livewire Components

Add trait to all components with RBAC:

```bash
# Akademik
- ProgramKegiatan.php ✅
- Pertemuan.php ✅
- Project.php ✅
- ProjectTeams.php
- PresensiPertemuan.php
- StatusAnggotaUjian.php
- HasilUjianPertemuan.php
- BankSoal/SoalPertemuan.php
- HasilUjian/Koreksi.php

# Organisasi
- TahunKepengurusan.php
- Department.php
- Anggota.php
- ProfilOrganisasi.php
- OpenRecruitment.php
- ControlUser.php
- PermissionMatrix.php

# Keuangan (belum ada RBAC)
# Perlengkapan (belum ada RBAC)
# Sekretaris (belum ada RBAC)
```

### Step 2: Replace `@can()` with `@if($this->can())`

Use Find & Replace in VSCode:

**Find:**

```
@can\("([^"]+)"\)
```

**Replace:**

```
@if($this->can('$1'))
```

**Find:**

```
@endcan
```

**Replace:**

```
@endif
```

### Step 3: Test Performance

```bash
# Clear cache
php artisan cache:clear
php artisan permission:cache-reset

# Test with Laravel Debugbar
# Check Models & Gate queries
```

---

## 🔍 Alternative Solutions (Not Used)

### Option 1: Eager Load Permissions in Query

```php
$data = User::with(['roles.permissions'])->paginate();
```

❌ Still checks permissions in blade for each item

### Option 2: Use Policy Classes

```php
class ProgramPolicy {
    public function view(User $user) {}
    public function edit(User $user) {}
}
```

❌ Same N+1 issue if not cached

### Option 3: Cache in Middleware

```php
class CachePermissions {
    public function handle($request, $next) {
        session(['permissions' => auth()->user()->getAllPermissions()]);
    }
}
```

✅ Good alternative, but Livewire trait is cleaner

---

## 📝 Notes

1. **Super Admin Bypass**: Super admin always returns `true` for any permission
2. **Memory Impact**: Minimal - storing ~50-100 permission names in array
3. **Cache Duration**: Per request/component lifecycle (auto-refresh on each page load)
4. **Compatibility**: Works with Spatie Laravel Permission package

---

## ⚠️ Important Reminders

1. **Always call `cacheUserPermissions()` in `mount()`**
2. **Use `@if($this->can())` not `@can()` in blade**
3. **For new components, add `WithPermissionCache` trait**
4. **Test after implementation to verify query reduction**

---

## 🧪 Testing Checklist

- [ ] Check Laravel Debugbar Models count (<50)
- [ ] Check Laravel Debugbar Gate count (0)
- [ ] Test login as different roles
- [ ] Verify buttons show/hide correctly
- [ ] Check page load time (<1s)
- [ ] Test permission changes (logout/login to refresh)

---

**Status**: ⏳ In Progress  
**Priority**: 🔴 CRITICAL (Performance Issue)  
**Impact**: 95% query reduction  
**Last Updated**: February 12, 2026
