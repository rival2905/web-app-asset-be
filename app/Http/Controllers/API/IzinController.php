<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DinasLuar;
use App\Models\Izin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FcmService;

class IzinController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    public function createIzin(Request $request)
    {
        try {

            $check = Izin::where('user_id', Auth::user()->id)
                ->where('tanggal', $request->tanggal)->first();

            if ($check) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah mengajukan izin pada tanggal tersebut'
                ], 401);
            }

            Izin::create([
                'user_id' => Auth::user()->id,
                'tanggal' => $request->tanggal,
                'type_izin' => $request->type_izin,
                'keterangan' => $request->keterangan ?? "-",
            ]);

            $fcm_token = User::where('id', Auth::user()->pengamat_id)->first()->fcm_token;
            $title = 'Pengajuan Izin';
            $message = Auth::user()->name . ' Mengajukan Izin ' . $request->type_izin;

            $this->fcmService->sendNotification($fcm_token, $title, $message);


            return response()->json([
                'status' => 'success',
                'message' => 'Izin berhasil diajukan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getIzin()
    {
        $user = Auth::user();
        if ($user->role == 'pekerja' || $user->role == 'mandor') {
            $data = Izin::where('user_id', Auth::user()->id)->get();
        } else {
            $pengamat_id = Auth::user()->id;
            $data = Izin::whereHas('user', function ($query) use ($pengamat_id) {
                $query->where('pengamat_id', $pengamat_id);
            })->with('user')->get();
            //            $data = Izin::with('user')->get();
            //            foreach ($data as $key => $value) {
            //                if ($value->user->pengamat_id != $user->id) {
            //
            //                    unset($data[$key]);
            //                }
            //            }
            //            $data = array_values($data->toArray());
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function approvalIzin(Request $request)
    {
        try {
            $izin = Izin::find($request->id);
            $fcm_token = User::where('id', $izin->user_id)->first()->fcm_token;
            $title = 'Pengajuan Izin';
            if ($request->status == 'Ditolak') {
                $izin->update([
                    'status' => $request->status,
                    'keterangan_status' => $request->keterangan
                ]);
                $message = 'Izin Ditolak - ' . $izin->type_izin;
            } else {
                $absen = Absensi::where('user_id', $izin->user_id)
                    ->where('tanggal', $izin->tanggal)->first();
                if ($absen) {
                    $absen->update([
                        'izin_id' => $izin->id,
                        'keterangan' => 'Izin - ' . $izin->type_izin
                    ]);
                } else {
                    Absensi::create([
                        'user_id' => $izin->user_id,
                        'tanggal' => $izin->tanggal,
                        'keterangan' => 'Izin - ' . $izin->type_izin,
                        'izin_id' => $izin->id,
                    ]);
                }
                $izin->update([
                    'status' => $request->status,
                    'keterangan_status' => $request->keterangan
                ]);
                $message = 'Izin Disetujui - ' . $izin->type_izin;
            }
            $this->fcmService->sendNotification($fcm_token, $title, $message);
            $izin->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Izin berhasil diupdate'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteIzin(Request $request)
    {
        try {
            $izin = Izin::find($request->id);
            $izin->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Izin berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
