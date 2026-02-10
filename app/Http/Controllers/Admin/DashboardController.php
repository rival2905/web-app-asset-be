<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\AssetMaterial;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total
        $totalUser = User::count();
        $totalAsset = Asset::count();
        $totalMaterial = AssetMaterial::count();

        $dashboardCards = [
            [
                'title' => 'Total User',
                'count' => $totalUser,
                'route' => '',
            ],
            [
                'title' => 'Total Asset',
                'count' => $totalAsset,
                'route' => '',
            ],
                
        ];

        // Data chart aset per bulan
        $asetPerBulan = Asset::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Buat array lengkap 12 bulan
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $asetPerBulan[$i] ?? 0;
        }

        return view('admin.dashboard.index', compact('dashboardCards', 'chartData'));
    }
}
