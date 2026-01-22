<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Absensi;
use App\Models\User;

class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {
       
        return view('admin.dashboard.index');

    }
}
