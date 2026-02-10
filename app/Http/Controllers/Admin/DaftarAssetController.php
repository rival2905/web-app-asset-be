<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class DaftarAssetController extends Controller
{
    public function index()
    {
        $assets = Asset::all();
        return view('admin.asset.daftar.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'item_code' => 'required',
            'stock'     => 'required|integer|min:0',
        ]);

        Asset::create($request->all());

        return redirect()
            ->route('admin.asset.index')
            ->with('success', 'Asset berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Asset::findOrFail($id)->delete();

        return redirect()
            ->route('admin.asset.index')
            ->with('success', 'Asset berhasil dihapus');
    }
}
