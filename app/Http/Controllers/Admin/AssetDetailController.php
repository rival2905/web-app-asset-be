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

        $detail = AssetDetail::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'id'      => $detail->id,
                'message' => 'Data Berhasil Disimpan!'
            ]);
        }

        return redirect()->route('admin.asset-detail.index')
            ->with('success', 'Data Berhasil Disimpan!');
    }

    public function edit($id)
    {
        $data   = AssetDetail::findOrFail($id);
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

        $data->update($request->all());

        return redirect()->route('admin.asset-detail.index')
            ->with('success', 'Data Berhasil Diperbaharui!');
    }

    public function destroy($id)
    {
        AssetDetail::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    // 🔥 AJAX: Untuk dropdown dinamis (ambil detail berdasarkan asset)
    public function getByAsset($asset_id)
    {
        return response()->json(
            AssetDetail::where('asset_id', $asset_id)->get()
        );
    }

    // 🔥 AJAX: Untuk modal quick add (simpan detail baru dari modal)
    public function storeAjax(Request $request)
    {
        $request->validate([
            'asset_id'    => 'required|exists:assets,id',
            'number_seri' => 'nullable|string|max:255',
            'condition'   => 'required|string|max:50',
        ]);

        $detail = AssetDetail::create([
            'asset_id'        => $request->asset_id,
            'number_seri'     => $request->number_seri,
            'production_year' => $request->production_year ?? null,
            'unit_price'      => $request->unit_price ?? null,
            'condition'       => $request->condition,
        ]);

        return response()->json([
            'id'          => $detail->id,
            'number_seri' => $detail->number_seri,
            'condition'   => $detail->condition,
            'message'     => 'Detail asset berhasil ditambahkan'
        ]);
    }
}