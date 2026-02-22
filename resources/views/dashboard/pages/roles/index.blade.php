@extends('dashboard.layouts.app')
{{-- @section('title', 'Manajemen Role') --}}
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-person-badge me-2 text-primary"></i>Manajemen Role</h4>
    @can('role.create')
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Role
    </a>
    @endcan
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Nama Role</th>
                    <th>Jumlah Permission</th>
                    <th>Jumlah User</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td class="ps-4 text-muted">{{ $roles->firstItem() + $loop->index }}</td>
                    <td><span class="fs-6 fw-normal">{{ $role->name }}</span></td>
                    <td><span class="badge bg-secondary text-white">{{ $role->permissions_count }} permissions</span></td>
                    <td><span class="badge bg-info text-dark">{{ $role->users_count }} users</span></td>
                    <td class="text-end pe-4">
                        @can('role.edit')
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-warning me-1">
                            <i class="fas fa-solid fa-pen"></i>
                        </a>
                        @endcan
                        @can('role.delete')
                        @if($role->name !== 'super-admin')
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline"
                              onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-solid fa-trash"></i></button>
                        </form>
                        @endif
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data role.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">{{ $roles->links() }}</div>
</div>
@endsection