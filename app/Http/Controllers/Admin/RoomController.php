<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Room;
use App\Models\Building;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('building')->get();
        return view('admin.master.room.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = "store";
        $buildings = Building::all();
        return view('admin.master.room.form', compact('action', 'buildings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:rooms,name',
            'building_id' => 'required|exists:buildings,id'
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'building_id' => $request->building_id,
        ];

        $save = Room::create($data);

        return redirect()
            ->route('admin.master-room.index')
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
        $data = Room::where('slug', $slug)->firstOrFail();
        $buildings = Building::all();

        return view('admin.master.room.form', compact('data', 'action', 'buildings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Room::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|unique:rooms,name,' . $data->id,
            'building_id' => 'required|exists:buildings,id'
        ]);

        $data->name = $request->name;
        $data->slug = Str::slug($request->name, '-');
        $data->building_id = $request->building_id;

        $save = $data->save();

        return redirect()
            ->route('admin.master-room.index')
            ->with($save ? 'success' : 'error',
                $save ? 'Data Berhasil Diperbaharui!' : 'Data Gagal Diperbaharui!'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Room::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
