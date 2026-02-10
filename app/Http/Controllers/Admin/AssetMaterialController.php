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
    public function index()
    {
        $asset_materials = AssetMaterial::all();
        return view('admin.asset.material.index', compact('asset_materials'));
    }

    public function create()
    {
        $action = "store";

        $assetCategories = AssetCategory::all();
        $brands = Brand::all();
        $series = Seri::all();

        return view('admin.asset.material.form', compact('action', 'assetCategories', 'brands', 'series'));
    }

    public function store(Request $request)
    {
        $request->validate([
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

        return redirect()->route('admin.asset-materials.index')
            ->with([$save ? 'success' : 'error' => $save ? 'Data Berhasil Disimpan!' : 'Data Gagal Disimpan!']);
    }

    public function edit($id)
    {
        $action = "update";
        $data = AssetMaterial::findOrFail($id);

        $assetCategories = AssetCategory::all();
        $brands = Brand::all();
        $series = Seri::all();

        return view('admin.asset.material.form', compact('data', 'action', 'assetCategories', 'brands', 'series'));
    }

    public function update(Request $request, $id)
    {
        $data = AssetMaterial::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:asset_materials,name,' . $data->id,
        ]);

        $data->name = $request->name;
        $data->slug = Str::slug($request->name, '-');
        $data->asset_category_id = $request->asset_category_id;
        $data->brand_id = $request->brand_id;
        $data->seri_id = $request->seri_id;

        $save = $data->save();

        return redirect()->route('admin.asset-materials.index')
            ->with([$save ? 'success' : 'error' => $save ? 'Data Berhasil Diperbaharui!' : 'Data Gagal Diperbaharui!']);
    }

    public function destroy($id)
    {
        $data = AssetMaterial::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
