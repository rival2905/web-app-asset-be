<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetMaterial;
use App\Models\Brand;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Display a listing of all assets.
     */
    public function index()
    {
        $assets = Asset::with(['assetMaterial', 'brand'])->get();

        return view('admin.asset.asset.index', compact('assets'));
    }

    /**
     * Display a list of available assets (stock > 0).
     */
    public function available()
    {
        $assets = Asset::with(['assetMaterial', 'brand'])
            ->where('stock', '>', 0)
            ->get();

        // PAKAI VIEW INDEX (karena available.blade.php tidak ada)
        return view('admin.asset.asset.index', compact('assets'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        $action = "store";
        $assetMaterials = AssetMaterial::all();
        $brands = Brand::all();

        return view('admin.asset.asset.form', compact(
            'action',
            'assetMaterials',
            'brands'
        ));
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'item_code' => 'nullable|unique:assets,item_code',
        ]);

        $save = Asset::create($request->only([
            'name',
            'size',
            'item_code',
            'stock',
            'description',
            'asset_material_id',
            'brand_id',
        ]));

        return redirect()
            ->route('admin.asset-asset.index')
            ->with(
                $save ? 'success' : 'error',
                $save ? 'Data Berhasil Disimpan!' : 'Data Gagal Disimpan!'
            );
    }

    /**
     * Show the form for editing an existing asset.
     */
    public function edit($id)
    {
        $action = "update";
        $data = Asset::findOrFail($id);
        $assetMaterials = AssetMaterial::all();
        $brands = Brand::all();

        return view('admin.asset.asset.form', compact(
            'data',
            'action',
            'assetMaterials',
            'brands'
        ));
    }

    /**
     * Update an existing asset in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Asset::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'item_code' => 'nullable|unique:assets,item_code,' . $data->id,
        ]);

        $data->update($request->only([
            'name',
            'size',
            'item_code',
            'stock',
            'description',
            'asset_material_id',
            'brand_id',
        ]));

        return redirect()
            ->route('admin.asset-asset.index')
            ->with('success', 'Data Berhasil Diperbaharui!');
    }

    /**
     * Remove an asset from storage.
     */
    public function destroy($id)
    {
        Asset::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
