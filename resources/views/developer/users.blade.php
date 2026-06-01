@extends('layouts.developer')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-people me-2 text-info"></i>Kelola Pengguna & Hak Akses (Role)</h4>
    <a href="{{ route('developer.dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Users Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Role Saat Ini</th>
                        <th class="pe-4 text-end" style="width: 250px;">Ganti Hak Akses (Role)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr>
                            <td class="ps-4 text-muted small">#{{ $u->id_user }}</td>
                            <td class="small fw-bold text-body py-3">
                                {{ $u->name }}
                                @if (auth()->id() === $u->id_user)
                                    <span class="badge text-bg-primary px-2 ms-1" style="font-size: 0.6rem;">Anda</span>
                                @endif
                            </td>
                            <td class="small text-muted font-monospace">{{ $u->username }}</td>
                            <td class="small text-muted">{{ $u->email }}</td>
                            <td class="small text-muted">{{ $u->no_hp ?? '-' }}</td>
                            <td>
                                @php
                                    $badge = match ($u->role) {
                                        'admin' => 'danger',
                                        'developer' => 'info text-dark',
                                        'user' => 'success',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $badge }} px-3 py-1 text-capitalize font-monospace" style="font-size: 0.7rem;">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <form method="POST" action="{{ route('developer.users.update-role', $u->id_user) }}" 
                                      class="d-flex align-items-center justify-content-end gap-2"
                                      onsubmit="return confirm('Apakah Anda yakin ingin mengganti role pengguna {{ $u->name }} menjadi ' + this.role.value.toUpperCase() + '?')">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="form-select form-select-sm" style="max-width: 140px;" required>
                                        <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="developer" {{ $u->role === 'developer' ? 'selected' : '' }}>Developer</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Ganti
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada data pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
