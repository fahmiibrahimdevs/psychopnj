<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Control User</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <h3>Manage User Access</h3>
                <div class="card-body">
                    <div class="show-entries">
                        <p class="show-entries-show">Show</p>
                        <select wire:model.live="lengthData" id="length-data">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                        </select>
                        <p class="show-entries-entries">Entries</p>
                    </div>
                    <div class="search-column">
                        <p>Search:</p>
                        <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Search here..." class="form-control" />
                    </div>
                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead class="tw-sticky tw-top-0">
                                <tr class="tw-text-gray-700">
                                    <th width="6%" class="text-center tw-whitespace-nowrap">No</th>
                                    <th class="tw-whitespace-nowrap">Name</th>
                                    <th class="tw-whitespace-nowrap">Email</th>
                                    <th class="tw-whitespace-nowrap">Roles</th>
                                    <th class="text-center tw-whitespace-nowrap">Status</th>
                                    <th class="text-center tw-whitespace-nowrap">Registered</th>
                                    <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="text-center">
                                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                        <td class="text-left">
                                            <div class="tw-flex tw-items-center tw-whitespace-nowrap">
                                                <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-blue-500 tw-flex tw-items-center tw-justify-center tw-text-white tw-font-bold">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="tw-ml-3">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-left">{{ $user->email }}</td>
                                        <td class="text-left">
                                            @if ($user->roles_names)
                                                @foreach (explode(", ", $user->roles_names) as $roleName)
                                                    <span class="badge badge-primary tw-mr-1">{{ $roleName }}</span>
                                                @endforeach
                                            @else
                                                <span class="tw-text-gray-400 tw-italic">No roles</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->active === "1")
                                                <span class="badge badge-success tw-px-3 tw-py-1">
                                                    <i class="fas fa-check"></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge badge-danger tw-px-3 tw-py-1">
                                                    <i class="fas fa-times"></i>
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="tw-whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($user->created_at)->format("d M Y") }}
                                        </td>
                                        <td class="tw-whitespace-nowrap">
                                            <button wire:click="toggleActive({{ $user->id }})" class="btn {{ $user->active === "1" ? "btn-warning" : "btn-success" }}" title="{{ $user->active === "1" ? "Deactivate" : "Activate" }}">
                                                @if ($user->active === "1")
                                                    <i class="fas fa-ban"></i>
                                                @else
                                                    <i class="fas fa-check"></i>
                                                @endif
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No data available in the table</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5 px-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push("scripts")
    <script>
        window.addEventListener('alert', (event) => {
            const data = event.detail[0];
            Swal.fire({
                icon: data.type,
                title: data.title,
                text: data.message,
                showConfirmButton: false,
                timer: 2000,
            });
        });
    </script>
@endpush
