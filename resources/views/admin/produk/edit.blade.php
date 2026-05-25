@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.produk.update', $produk) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.produk._form', ['produk' => $produk])
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Update
                </button>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
