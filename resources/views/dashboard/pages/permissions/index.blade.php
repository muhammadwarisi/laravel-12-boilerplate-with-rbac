@extends('dashboard.layouts.app')
{{-- @section('title', 'Manajemen Permission') --}}
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-key me-2 text-primary"></i>Manajemen Permission</h4>
    @can('permission.create')
    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Permission
    </a>
    @endcan
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Nama Permission</th>
                    <th>Grup</th>
                    <th>Digunakan di Role</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $permission)
                <tr>
                    <td class="ps-4 text-muted">{{ $permissions->firstItem() + $loop->index }}</td>
                    <td><code>{{ $permission->name }}</code></td>
                    <td><span class="badge bg-light text-dark border">{{ explode('.', $permission->name)[0] }}</span></td>
                    <td><span class="badge bg-secondary text-white">{{ $permission->roles_count }} roles</span></td>
                    <td class="text-end pe-4">
                        @can('permission.edit')
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-outline-warning me-1">
                            <i class="fas fa-solid fa-pen"></i>
                        </a>
                        @endcan
                        @can('permission.delete')
                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" class="d-inline"
                              onsubmit="return confirm('Hapus permission {{ $permission->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-solid fa-trash"></i></button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data permission.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">{{ $permissions->links() }}</div>
</div>
@endsection