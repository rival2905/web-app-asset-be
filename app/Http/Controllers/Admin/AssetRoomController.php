<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AssetRoom;

class AssetRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = AssetRoom::get();
        return view('admin.asset.room.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = "store";
        return view('admin.asset.room.form', compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:asset_rooms,name'
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ];

        $save = AssetRoom::create($data);

        return redirect()
            ->route('admin.asset-room.index')
            ->with($save ? 'success' : 'error',
                $save ? 'Data Berhasil Disimpan!' : 'Data Gagal Disimpan!'
            );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $action = "update";
        $data = AssetRoom::where('slug', $slug)->firstOrFail();

        return view('admin.asset.room.form', compact('data', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = AssetRoom::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|unique:asset_rooms,name,' . $data->id
        ]);

        $data->name = $request->name;
        $data->slug = Str::slug($request->name, '-');

        $save = $data->save();

        return redirect()
            ->route('admin.asset-room.index')
            ->with($save ? 'success' : 'error',
                $save ? 'Data Berhasil Diperbaharui!' : 'Data Gagal Diperbaharui!'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = AssetRoom::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
