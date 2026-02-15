<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Matrix Role & Permission</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <h3>Pengaturan Hak Akses (RBAC)</h3>
                <div class="card-body">
                    <div class="search-column">
                        <p>Search:</p>
                        <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Search here..." class="form-control" />
                    </div>

                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full table-striped table-hover tw-align-middle">
                            <thead class="tw-sticky tw-top-0 bg-light">
                                <tr class="tw-text-gray-700">
                                    <th class="tw-py-3 tw-px-4 tw-uppercase tw-text-sm tw-font-bold tw-text-gray-600 tw-whitespace-nowrap">Module / Permission</th>
                                    @foreach ($roles as $role)
                                        <th class="text-center tw-whitespace-nowrap tw-py-3 tw-capitalize tw-font-bold text-dark">
                                            {{ str_replace("_", " ", $role->name) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modules as $moduleName => $permissions)
                                    <!-- Module Header -->
                                    <tr class="bg-primary text-white">
                                        <td colspan="{{ count($roles) + 1 }}" class="tw-py-2 tw-px-4 tw-font-bold tw-bg-blue-600 text-white">
                                            <i class="fas fa-layer-group tw-mr-2"></i>
                                            {{ $moduleName }} Module
                                        </td>
                                    </tr>

                                    <!-- Permission Rows -->
                                    @foreach ($permissions as $perm)
                                        @php
                                            // Ambil nama action (setelah titik pertama)
                                            $parts = explode(".", $perm->name, 2);
                                            $actionName = isset($parts[1])
                                                ? ucfirst(str_replace("_", " ", $parts[1]))
                                                : ucfirst($perm->name);
                                        @endphp

                                        <tr>
                                            <td class="tw-pl-8 tw-py-2 tw-text-gray-700 tw-font-medium">
                                                {{ $actionName }}
                                                <div class="tw-text-xs tw-text-gray-400 tw-font-light">{{ $perm->name }}</div>
                                            </td>

                                            @foreach ($roles as $role)
                                                <td class="text-center tw-align-middle">
                                                    @if ($this->can("permission_matrix.manage"))
                                                        <label class="custom-switch tw-pl-0 tw-mb-0 tw-cursor-pointer">
                                                            <input type="checkbox" wire:click="togglePermission({{ $role->id }}, {{ $perm->id }})" @if($role->hasPermissionTo($perm->name)) checked @endif class="custom-switch-input" />
                                                            <span class="custom-switch-indicator"></span>
                                                        </label>
                                                    @else
                                                        @if ($role->hasPermissionTo($perm->name))
                                                            <i class="fas fa-check-circle text-success"></i>
                                                        @else
                                                            <i class="fas fa-times-circle text-danger"></i>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push("scripts")
    <!-- Simple Toast Notification Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
           @this.on('saved', () => {
               if (typeof iziToast !== 'undefined') {
                   iziToast.success({
                        title: 'Tersimpan!',
                        message: 'Hak akses berhasil diperbarui.',
                        position: 'topRight'
                    });
               } else {
                   console.log('Permission updated successfully');
               }
           });
        });
    </script>
@endpush
