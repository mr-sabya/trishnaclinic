<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Staff & User Directory</h5>
        <a href="{{ route('admin.users.create') }}" wire:navigate class="btn btn-primary btn-sm">Add New Staff</a>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <select wire:model.live="perPage" class="form-select w-auto">
                <option value="10">10</option>
                <option value="25">25</option>
            </select>
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control w-25" placeholder="Search name or phone...">
        </div>

        <table class="table table-hover border">
            <thead class="table-light">
                <tr>
                    <th wire:click="setSortBy('name')" style="cursor:pointer">Name</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td><span class="badge bg-soft-info text-info">{{ $user->role->value }}</span></td>
                    <td>{{ $user->staff->adminDepartment->name ?? 'N/A' }}</td>
                    <td>{{ $user->phone }}</td>
                    <td><span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.users.edit', $user->id) }}" wire:navigate class="btn btn-sm btn-outline-primary"><i class="ri-pencil-line"></i></a>
                        <button onclick="confirm('Delete user?') || event.stopImmediatePropagation()" wire:click="delete({{ $user->id }})" class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>