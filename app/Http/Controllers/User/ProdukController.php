<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(Request $request): View
    {
        $query = Produk::query()->where('stok', '>', 0);

        if ($request->filled('q')) {
            $query->where('nama_produk', 'like', '%'.$request->q.'%');
        }

        $produks = $query->orderByDesc('id_produk')->paginate(12)->withQueryString();

        return view('user.produk.index', compact('produks'));
    }

    public function show(Produk $produk): View
    {
        return view('user.produk.show', compact('produk'));
    }
}
