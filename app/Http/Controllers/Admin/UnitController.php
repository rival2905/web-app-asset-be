<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MasterUnit;

class UnitController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $units = MasterUnit::get();
        
        return view('admin.unit.index', compact('units'));

    }
}
