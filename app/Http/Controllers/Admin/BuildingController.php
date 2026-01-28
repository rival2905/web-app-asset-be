<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Building;
use App\Models\Unit;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TAMBAHAN: eager load relasi unit
        $buildings = Building::with('unit')->get();

        return view('admin.asset.building.index', compact('buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = "store";

        // TAMBAHAN: ambil data unit
        $units = Unit::all();

        return view('admin.asset.building.form', compact('action', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:buildings'
        ]);

        // TAMBAHAN: simpan unit_id
        $data = [
            'name'    => $request->name,
            'slug'    => Str::slug($request->input('name'), '-'),
            'unit_id' => $request->unit_id
        ];

        $save = Building::create($data);

        if ($save) {
            return redirect()->route('admin.building.index')
                ->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            return redirect()->route('admin.building.index')
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
        $data = Building::where('slug', $slug)->first();

        // TAMBAHAN: ambil data unit
        $units = Unit::all();

        return view('admin.asset.building.form', compact('data', 'action', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Building::find($id);

        $this->validate($request, [
            'name' => 'required|unique:buildings,name,' . $data->id
        ]);

        $data->name = $request->name;
        $data->slug = Str::slug($request->input('name'), '-');

        // TAMBAHAN: update unit_id
        $data->unit_id = $request->unit_id;

        $save = $data->save();

        if ($save) {
            return redirect()->route('admin.building.index')
                ->with(['success' => 'Data Berhasil Diperbaharui!']);
        } else {
            return redirect()->route('admin.building.index')
                ->with(['error' => 'Data Gagal Diperbaharui!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Building::findOrFail($id);
        $data->delete();

        if ($data) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }
}
