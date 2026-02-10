<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AssetRealization;
use App\Models\Asset;
use App\Models\Room;

class AssetRealizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $realizations = AssetRealization::get();
        return view('admin.asset.realization.index', compact('realizations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = "store";
        $assets = Asset::all();
        $rooms = Room::all();

        return view('admin.asset.realization.form', compact(
            'action',
            'assets',
            'rooms'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validator
        $this->validate($request, [
            'asset_id' => 'required',
            'date' => 'required|date',
            'room_id' => 'required',
            'detail_asset' => 'required'
        ]);

        // nampung data
        $data = [
            'asset_id' => $request->asset_id,
            'date' => $request->date,
            'room_id' => $request->room_id,
            'detail_asset' => $request->detail_asset,
        ];

        $save = AssetRealization::create($data);

        return redirect()->route('admin.asset-realization.index')
            ->with($save ? 'success' : 'error',
                $save ? 'Data Berhasil Disimpan!' : 'Data Gagal Disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $action = "update";

        // GANTI slug jadi find by ID
        $data = AssetRealization::findOrFail($id);

        $assets = Asset::all();
        $rooms = Room::all();

        return view('admin.asset.realization.form', compact(
            'data',
            'action',
            'assets',
            'rooms'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = AssetRealization::findOrFail($id);

        // validator
        $this->validate($request, [
            'asset_id' => 'required',
            'date' => 'required|date',
            'room_id' => 'required',
            'detail_asset' => 'required'
        ]);

        $data->asset_id = $request->asset_id;
        $data->date = $request->date;
        $data->room_id = $request->room_id;
        $data->detail_asset = $request->detail_asset;

        $save = $data->save();

        return redirect()->route('admin.asset-realization.index')
            ->with($save ? 'success' : 'error',
                $save ? 'Data Berhasil Diperbaharui!' : 'Data Gagal Diperbaharui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = AssetRealization::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => $data ? 'success' : 'error'
        ]);
    }
}
