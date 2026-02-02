<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\AssetRealizations;

class AssetRealizationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $realizations = AssetRealizations::get();
        
        return view('admin.asset.realization.index', compact('realizations'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $action = "store";

        return view('admin.asset.realization.form', compact('action'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validator
        $this->validate($request, [
            'name' => 'required|unique:asset_realizations'
        ]);

        //nampung
        $data=[
            'name'=>$request->name,
            'slug'=> Str::slug($request->input('name'), '-'),
        ];
    
        $save = AssetRealizations::create($data);
        if($save){
            //redirect dengan pesan sukses
            return redirect()->route('admin.asset-realization.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.asset-realization.index')->with(['error' => 'Data Gagal Disimpan!']);
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
        //
        $action = "update";
        $data = AssetRealizations::where('slug',$slug)->first();
        return view('admin.asset.realization.form', compact('data', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = AssetRealizations::find($id);
        //validator
        $this->validate($request, [
            'name' => 'required|unique:asset_realizations,name,'.$data->id
        ]);
        
        $data->name = $request->name;  
        $data->slug = Str::slug($request->input('name'), '-');
      
        $save = $data->save();

        if($save){
            //redirect dengan pesan sukses
            return redirect()->route('admin.asset-realization.index')->with(['success' => 'Data Berhasil Diperbaharui!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.asset-realization.index')->with(['error' => 'Data Gagal Diperbaharui!']);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        //
        $data = AssetRealizations::findOrFail($id);
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
