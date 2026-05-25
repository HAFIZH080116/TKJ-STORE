@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.produk._form')
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
