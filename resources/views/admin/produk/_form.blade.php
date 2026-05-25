@php
    $isEdit = isset($produk);
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
            <input type="text" name="nama_produk" id="nama_produk"
                   class="form-control @error('nama_produk') is-invalid @enderror"
                   value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" required>
            @error('nama_produk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="harga_satuan" class="form-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga_satuan" id="harga_satuan" step="0.01" min="0"
                       class="form-control @error('harga_satuan') is-invalid @enderror"
                       value="{{ old('harga_satuan', $produk->harga_satuan ?? '') }}" required>
                @error('harga_satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stok" id="stok" min="0"
                       class="form-control @error('stok') is-invalid @enderror"
                       value="{{ old('stok', $produk->stok ?? 0) }}" required>
                @error('stok')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4"
                      class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="gambar" class="form-label">
                Gambar Produk @if (! $isEdit)<span class="text-danger">*</span>@endif
            </label>
            <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/jpg,image/webp"
                   class="form-control @error('gambar') is-invalid @enderror"
                   {{ $isEdit ? '' : 'required' }}>
            @error('gambar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">JPG, PNG, WEBP. Maks. 2MB.</small>
        </div>
        @if ($isEdit && $produk->gambar)
            <div class="border rounded p-2 bg-light text-center">
                <img src="{{ asset('storage/'.$produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                     class="img-fluid rounded" style="max-height:180px">
                <p class="small text-muted mt-2 mb-0">Gambar saat ini</p>
            </div>
        @endif
    </div>
</div>
