<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RekapController extends Controller
{
    public function rekapAbsen($id, $periode)
    {


        $dataDb = Absensi::where('user_id', $id)->whereBetween('tanggal', [$periode . '-01', $periode . '-31'])->get();
        $countDateThisMonth = date('t', strtotime($periode . '-01'));

        $dateThisMonth = [];
        for ($i = 1; $i <= $countDateThisMonth; $i++) {
            $date =  date('Y-m') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

            $time = strtotime($date);

            $day = date('l', $time);

            if ($day != 'Sunday') {
                $dateThisMonth[] = $periode . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            }

            // if ($day != 'Sunday' && !$found) {
            //     $fixCountThisMonth = $fixCountThisMonth + 1;
            // }
        }

        $dateThisMonth = array_values($dateThisMonth);
        $dinasLuar = 0;
        $masuk = 0;
        $izin = 0;
        $alpha = 0;
        $terlambat = 0;


        foreach ($dataDb as $key => $value) {
            if (str_contains($value->type, 'Dinas')) {
                $dinasLuar++;
            } else if ($value->type == 'Masuk') {
                $masuk++;
                if ($value->jam_masuk > '07:30:00') {
                    $terlambat++;
                }
            } else if (str_contains($value->type, 'Izin')) {
                $izin++;
            }
        }
        $alpha = count($dateThisMonth) - $masuk - $izin - $dinasLuar;
        $data = [
            'masuk' => $masuk,
            'izin' => $izin,
            'alpha' => $alpha > 0 ? $alpha : 0,
            'dinas_luar' => $dinasLuar,
            'terlambat' => $terlambat,
            'totalThisMonth' => count($dateThisMonth),
            'data' => $dataDb
        ];
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function checkus(Request $request)
    {
        $identifier = $request->query('email');

        if (!$identifier) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Parameter email/NIP tidak boleh kosong'
            ], 400);
        }

        $user = User::where('email', $identifier)
                    ->orWhere('nip', $identifier)
                    ->first();

        if ($user) {
            return response()->json([
                'status'  => 'success',
                'message' => 'User ditemukan',
                'data'    => [
                    'nama'  => $user->name, // atau $user->nama
                    'email' => $user->email,
                    'nip'   => $user->nip,
                    'user_id'   => $user->id,
                    // Tambahkan field lain yang dibutuhkan oleh view upload Anda
                ]
            ], 200);
        }else{
            $obj = (object) array('email' => $identifier);
            return response()->json([
                'status' => 'failed',
                'data' => $obj
            ]);
        }
        
    }

}
