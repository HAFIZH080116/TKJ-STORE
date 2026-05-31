<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(Request $request): View
    {
        $query = Produk::query();

        if ($request->filled('q')) {
            $query->where('nama_produk', 'like', '%'.$request->q.'%');
        }

        $produks = $query->orderByDesc('id_produk')->paginate(10)->withQueryString();

        return view('admin.produk.index', compact('produks'));
    }

    public function create(): View
    {
        return view('admin.produk.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), $this->messages());

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk = Produk::create($validated);

        \App\Models\ActivityLog::record("Menambahkan produk baru: {$produk->nama_produk}");

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk): View
    {
        return view('admin.produk.show', compact('produk'));
    }

    public function edit(Produk $produk): View
    {
        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validate($this->rules($produk->id_produk), $this->messages());

        if ($request->hasFile('gambar')) {
            $this->deleteGambar($produk->gambar);
            $validated['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($validated);

        \App\Models\ActivityLog::record("Memperbarui detail produk: {$produk->nama_produk}");

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        if ($produk->detailTransaksi()->exists()) {
            return redirect()
                ->route('admin.produk.index')
                ->with('error', 'Produk tidak dapat dihapus karena sudah digunakan dalam transaksi.');
        }

        $namaProduk = $produk->nama_produk;
        $this->deleteGambar($produk->gambar);
        $produk->delete();

        \App\Models\ActivityLog::record("Menghapus produk: {$namaProduk}");

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'nama_produk' => ['required', 'string', 'max:150'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => [$ignoreId ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    private function messages(): array
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_satuan.required' => 'Harga satuan wajib diisi.',
            'harga_satuan.min' => 'Harga satuan minimal 0.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.min' => 'Stok minimal 0.',
            'gambar.required' => 'Gambar produk wajib diunggah.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar: jpeg, png, jpg, webp.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    private function deleteGambar(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
