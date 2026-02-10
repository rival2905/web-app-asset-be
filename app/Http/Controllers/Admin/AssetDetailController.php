<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetDetail;

class AssetDetailController extends Controller
{
    public function index()
    {
        $details = AssetDetail::with('asset')->get();
        return view('admin.asset.detail.index', compact('details'));
    }

    public function create()
    {
        $action = 'store';
        $assets = Asset::all();
        return view('admin.asset.detail.form', compact('action', 'assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id'        => 'required|exists:assets,id',
            'number_seri'     => 'nullable|string|max:255',
            'production_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'unit_price'      => 'nullable|numeric',
            'condition'       => 'required|string|max:50',
        ]);

        AssetDetail::create([
            'asset_id'        => $request->asset_id,
            'number_seri'     => $request->number_seri,
            'production_year' => $request->production_year,
            'unit_price'      => $request->unit_price,
            'condition'       => $request->condition,
        ]);

        return redirect()->route('admin.asset-detail.index')
                         ->with('success', 'Data Berhasil Disimpan!');
    }

    public function edit($id)
    {
        $data   = AssetDetail::with('asset')->findOrFail($id);
        $assets = Asset::all();
        $action = 'update';

        return view('admin.asset.detail.form', compact('data', 'action', 'assets'));
    }

    public function update(Request $request, $id)
    {
        $data = AssetDetail::findOrFail($id);

        $request->validate([
            'number_seri'     => 'nullable|string|max:255',
            'production_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'unit_price'      => 'nullable|numeric',
            'condition'       => 'required|string|max:50',
        ]);

        $data->update([
            'number_seri'     => $request->number_seri,
            'production_year' => $request->production_year,
            'unit_price'      => $request->unit_price,
            'condition'       => $request->condition,
        ]);

        return redirect()->route('admin.asset-detail.index')
                         ->with('success', 'Data Berhasil Diperbaharui!');
    }

    public function destroy($id)
    {
        $data = AssetDetail::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus!'
        ]);
    }
}
