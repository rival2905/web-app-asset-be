<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\AssetRealization;

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
        return view('admin.asset.realization.form', compact('action'));
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

        if ($save) {
            return redirect()->route('admin.asset-realization.index')
                ->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            return redirect()->route('admin.asset-realization.index')
                ->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $action = "update";
        $data = AssetRealization::where('slug', $slug)->first();
        return view('admin.asset.realization.form', compact('data', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = AssetRealization::find($id);

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

        if ($save) {
            return redirect()->route('admin.asset-realization.index')
                ->with(['success' => 'Data Berhasil Diperbaharui!']);
        } else {
            return redirect()->route('admin.asset-realization.index')
                ->with(['error' => 'Data Gagal Diperbaharui!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = AssetRealization::findOrFail($id);
        $data->delete();

        if ($data) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
