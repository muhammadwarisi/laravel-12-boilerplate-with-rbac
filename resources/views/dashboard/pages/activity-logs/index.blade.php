{{-- resources/views/activity-logs/index.blade.php --}}
@extends('dashboard.layouts.app') {{-- atau layout utama SB Admin 2 --}}

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Activity Log</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Aktivitas</h6>
            <div>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-sync-alt"></i> Reset Filter
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Form Filter -->
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="event">Event</label>
                            <select name="event" id="event" class="form-control">
                                <option value="">Semua Event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                        {{ ucfirst($event) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="user">User</label>
                            <input type="text" name="user" id="user" class="form-control" 
                                   placeholder="Cari nama user..." value="{{ request('user') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subject_type">Model</label>
                            <select name="subject_type" id="subject_type" class="form-control">
                                <option value="">Semua Model</option>
                                @foreach($subjectTypes as $type)
                                    <option value="{{ $type }}" {{ request('subject_type') == $type ? 'selected' : '' }}>
                                        {{ class_basename($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabel Log -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Subjek</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $key => $log)
                            <tr>
                                <td>{{ $logs->firstItem() + $key }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    @if($log->causer)
                                        {{ $log->causer->name ?? $log->causer->email }}
                                    @else
                                        <span class="badge badge-secondary">System</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeColor = match($log->event) {
                                            'created' => 'success',
                                            'updated' => 'warning',
                                            'deleted' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $badgeColor }} badge-pill px-3 py-2">
                                        {{ ucfirst($log->event) }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->subject)
                                        {{ class_basename($log->subject_type) }}
                                        <small class="d-block text-muted">ID: {{ $log->subject_id }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    <!-- Tombol Detail (modal atau link) -->
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" 
                                            data-target="#detailModal{{ $log->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada aktivitas ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail untuk setiap log (opsional) -->
@foreach($logs as $log)
    <div class="modal fade" id="detailModal{{ $log->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Aktivitas #{{ $log->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>ID:</strong> {{ $log->id }}</li>
                        <li class="list-group-item"><strong>Waktu:</strong> {{ $log->created_at->format('d/m/Y H:i:s') }}</li>
                        <li class="list-group-item"><strong>User:</strong> {{ $log->causer ? $log->causer->name : 'System' }}</li>
                        <li class="list-group-item"><strong>Event:</strong> {{ $log->event }}</li>
                        <li class="list-group-item"><strong>Deskripsi:</strong> {{ $log->description }}</li>
                        <li class="list-group-item"><strong>Subject Type:</strong> {{ $log->subject_type }}</li>
                        <li class="list-group-item"><strong>Subject ID:</strong> {{ $log->subject_id }}</li>
                        @if($log->properties->count())
                            <li class="list-group-item">
                                <strong>Properties:</strong>
                                <pre class="bg-light p-2 mt-1" style="max-height:200px; overflow-y:auto;">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection