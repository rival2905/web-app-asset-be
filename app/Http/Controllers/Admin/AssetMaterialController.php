<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\AssetMaterial;
use App\Models\AssetCategory;
use App\Models\Brand;
use App\Models\Seri;

class AssetMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asset_materials = AssetMaterial::get();
        return view('admin.asset.material.index', compact('asset_materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = "store";

        $assetCategories = AssetCategory::all();
        $brands = Brand::all();
        $series = Seri::all();

        return view(
            'admin.asset.material.form',
            compact('action', 'assetCategories', 'brands', 'series')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:asset_materials'
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'asset_category_id' => $request->asset_category_id,
            'brand_id' => $request->brand_id,
            'seri_id' => $request->seri_id,
        ];

        $save = AssetMaterial::create($data);

        if ($save) {
            return redirect()->route('admin.asset-material.index')
                ->with(['success' => 'Data Berhasil Disimpan!']);
        }

        return redirect()->route('admin.asset-material.index')
            ->with(['error' => 'Data Gagal Disimpan!']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $action = "update";
        $data = AssetMaterial::where('slug', $slug)->firstOrFail();

        $assetCategories = AssetCategory::all();
        $brands = Brand::all();
        $series = Seri::all();

        return view(
            'admin.asset.material.form',
            compact('data', 'action', 'assetCategories', 'brands', 'series')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = AssetMaterial::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|unique:asset_materials,name,' . $data->id
        ]);

        $data->name = $request->name;
        $data->slug = Str::slug($request->name, '-');
        $data->asset_category_id = $request->asset_category_id;
        $data->brand_id = $request->brand_id;
        $data->seri_id = $request->seri_id;

        $save = $data->save();

        if ($save) {
            return redirect()->route('admin.asset-material.index')
                ->with(['success' => 'Data Berhasil Diperbaharui!']);
        }

        return redirect()->route('admin.asset-material.index')
            ->with(['error' => 'Data Gagal Diperbaharui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = AssetMaterial::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
