@extends('dashboard.layouts.app')
{{-- @section('title', 'Manajemen User') --}}
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Manajemen User</h4>
    @can('user.create')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah User
    </a>
    @endcan
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 py-3">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama atau email..." style="max-width:300px">
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
            @if(request('search'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Dibuat</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="ps-4 text-muted">{{ $users->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="fw-semibold">{{ $user->name }}</div>
                    </td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="badge bg-primary badge-role text-white">{{ $role->name }}</span>
                        @empty
                            <span class="text-muted small">-</span>
                        @endforelse
                    </td>
                    <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-end pe-4">
                        @can('user.edit')
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning me-1">
                            <i class="fas fa-solid fa-pen"></i>
                        </a>
                        @endcan
                        @can('user.delete')
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline"
                              onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">
        {{ $users->links() }}
    </div>
</div>
@endsection