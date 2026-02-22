@extends('dashboard.layouts.app')
{{-- @section('title', 'Edit Permission') --}}
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Edit Permission</h4>
</div>
<div class="card shadow-sm border-0" style="max-width:500px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Permission <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $permission->name) }}">
                <div class="form-text">Mengubah nama permission akan mempengaruhi semua role yang menggunakannya.</div>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection