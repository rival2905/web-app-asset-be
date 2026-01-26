<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\AssetType;

class AssetTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $types = AssetType::get();
        
        return view('admin.asset.type.index', compact('types'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $action = "store";

        return view('admin.asset.type.form', compact('action'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required|unique:asset_types'
        ]);
        $data=[
            'name'=>$request->name,
            'slug'=> Str::slug($request->input('name'), '-'),
        ];
    
        $save = AssetType::create($data);

        if($save){
            //redirect dengan pesan sukses
            return redirect()->route('admin.asset-type.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.asset-type.index')->with(['error' => 'Data Gagal Disimpan!']);
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
    public function edit($id)
    {
        //
        $action = "update";
        $data = AssetType::find($id);
        return view('admin.asset.type.form', compact('data', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = AssetType::find($id);

        $this->validate($request, [
            'name' => 'required|unique:asset_types,name,'.$data->id
        ]);
        
        $data->name = $request->name;
        $data->slug = Str::slug($request->input('name'), '-');
 
        $save = $data->save();

        if($save){
            //redirect dengan pesan sukses
            return redirect()->route('admin.asset-type.index')->with(['success' => 'Data Berhasil Diperbaharui!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.asset-type.index')->with(['error' => 'Data Gagal Diperbaharui!']);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        //
        $data = AssetType::findOrFail($id);
        $data->delete();

        if($data){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
