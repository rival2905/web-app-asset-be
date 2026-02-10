<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetRealization;
use App\Models\Asset;
use App\Models\AssetDetail;
use App\Models\Room;

class AssetRealizationController extends Controller
{
    public function index()
    {
        $realizations = AssetRealization::with(['asset', 'room', 'detailAsset'])->get();
        return view('admin.asset.realization.index', compact('realizations'));
    }

    public function create()
    {
        $action = "store";
        $assets = Asset::all();
        $rooms = Room::all();

        return view('admin.asset.realization.form', compact('action', 'assets', 'rooms'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'asset_id'        => 'required|exists:assets,id',
            'date'            => 'required|date',
            'room_id'         => 'required|exists:rooms,id',
            'detail_asset_id' => 'required|exists:asset_details,id'
        ]);

        AssetRealization::create([
            'asset_id'        => $request->asset_id,
            'date'            => $request->date,
            'room_id'         => $request->room_id,
            'detail_asset_id' => $request->detail_asset_id,
        ]);

        return redirect()->route('admin.asset-realization.index')
            ->with('success', 'Data Berhasil Disimpan!');
    }

    public function edit($id)
    {
        $action = "update";
        $data = AssetRealization::findOrFail($id);
        $assets = Asset::all();
        $rooms = Room::all();

        return view('admin.asset.realization.form', compact('data', 'action', 'assets', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $data = AssetRealization::findOrFail($id);

        $this->validate($request, [
            'asset_id'        => 'required|exists:assets,id',
            'date'            => 'required|date',
            'room_id'         => 'required|exists:rooms,id',
            'detail_asset_id' => 'required|exists:asset_details,id'
        ]);

        $data->update([
            'asset_id'        => $request->asset_id,
            'date'            => $request->date,
            'room_id'         => $request->room_id,
            'detail_asset_id' => $request->detail_asset_id,
        ]);

        return redirect()->route('admin.asset-realization.index')
            ->with('success', 'Data Berhasil Diperbaharui!');
    }

    public function destroy($id)
    {
        AssetRealization::findOrFail($id)->delete();
        return response()->json(['status' => 'success']);
    }

    // AJAX: Ambil detail asset berdasarkan asset_id
    public function getDetailsByAsset($asset_id)
    {
        $details = AssetDetail::where('asset_id', $asset_id)
            ->select('id', 'number_seri', 'condition', 'production_year')
            ->get();

        return response()->json($details);
    }
}