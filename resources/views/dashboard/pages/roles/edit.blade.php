@extends('dashboard.layouts.app')
{{-- @section('title', 'Edit Role') --}}
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Edit Role: {{ $role->name }}</h4>
</div>

<div class="card shadow-sm border-0" style="max-width:700px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="form-label fw-semibold">Nama Role <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $role->name) }}"
                       {{ $role->name === 'super-admin' ? 'readonly' : '' }}>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Permissions</label>
                @foreach($permissions as $group => $perms)
                @php $allChecked = $perms->every(fn($p) => in_array($p->id, $rolePermissions)); @endphp
                <div class="card border mb-2">
                    <div class="card-header bg-light py-2">
                        <div class="form-check">
                            <input class="form-check-input toggle-group" type="checkbox" data-group="{{ $group }}"
                                   id="group_{{ $group }}" {{ $allChecked ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-capitalize" for="group_{{ $group }}">{{ $group }}</label>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="row g-2">
                            @foreach($perms as $permission)
                            <div class="col-sm-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input perm-{{ $group }}" type="checkbox"
                                           name="permissions[]" value="{{ $permission->name }}"
                                           id="perm_{{ $permission->id }}"
                                           {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.querySelectorAll('.toggle-group').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const group = this.dataset.group;
        document.querySelectorAll(`.perm-${group}`).forEach(cb => cb.checked = this.checked);
    });
});
</script>
@endpush
@endsection