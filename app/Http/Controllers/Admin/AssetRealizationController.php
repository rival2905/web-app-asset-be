<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Room;
use App\Models\AssetDetail;
use App\Models\AssetRealization;

class AssetRealizationController extends Controller
{
    public function index()
    {
        $realizations = AssetRealization::with(['asset', 'room', 'assetDetail'])->get();
        return view('admin.realization.index', compact('realizations'));
    }

    public function create()
    {
        $assets = Asset::all();
        $rooms = Room::all(); // 🔥 TAMBAHKAN INI (Wajib ada untuk form)
        
        return view('admin.realization.create', compact('assets', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'date' => 'required|date',
            'room_id' => 'nullable|exists:rooms,id',
            'detail_asset_id' => 'nullable|exists:asset_details,id',
        ]);

        AssetRealization::create($request->all());

        // 🔥 PERBAIKI: Sesuaikan nama route dengan web.php
        return redirect()->route('admin.asset-realization.index')
            ->with('success', 'Realisasi Asset berhasil disimpan!');
    }

    // 🔥 AJAX: Get Rooms by Asset
    public function getRoomsByAsset($asset_id)
    {
        try {
            $rooms = Room::all(['id', 'name']);
            return response()->json($rooms);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 🔥 AJAX: Get Asset Details by Asset
    public function getDetailsByAsset($asset_id)
    {
        try {
            $details = AssetDetail::where('asset_id', $asset_id)
                ->select('id', 'number_seri', 'production_year', 'condition')
                ->get();
            return response()->json($details);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 🔥 TAMBAHAN: Method Edit
    public function edit($id)
    {
        $data = AssetRealization::findOrFail($id);
        $assets = Asset::all();
        $rooms = Room::all(); // Kirim juga rooms
        
        return view('admin.realization.edit', compact('data', 'assets', 'rooms'));
    }

    // 🔥 TAMBAHAN: Method Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'date' => 'required|date',
            'room_id' => 'nullable|exists:rooms,id',
            'detail_asset_id' => 'nullable|exists:asset_details,id',
        ]);

        $data = AssetRealization::findOrFail($id);
        $data->update($request->all());

        // 🔥 PERBAIKI: Sesuaikan nama route
        return redirect()->route('admin.asset-realization.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    // 🔥 TAMBAHAN: Method Destroy
    public function destroy($id)
    {
        AssetRealization::findOrFail($id)->delete();

        // Return JSON untuk AJAX di view
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus!'
        ]);
    }
}