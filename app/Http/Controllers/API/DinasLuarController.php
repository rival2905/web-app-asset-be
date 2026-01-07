<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DinasLuar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FcmService;

class DinasLuarController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    public function createDinasLuar(Request $request)
    {


        $check = DinasLuar::where('user_id', Auth::user()->id)
            ->where('tanggal_mulai', $request->tgl_mulai)->first();

        if ($check && $check->status != 'Ditolak') {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah mengajukan dinas luar pada tanggal tersebut'
            ], 401);
        }

        DinasLuar::create([
            'user_id' => Auth::user()->id,
            'tanggal_mulai' => $request->tgl_mulai,
            'tanggal_selesai' => $request->tgl_selesai < $request->tgl_mulai ? $request->tgl_mulai : $request->tgl_selesai,
            'type_dl' => $request->type_dl,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tujuan' => $request->tujuan ?? 'WFA',
            'kegiatan' => $request->kegiatan,
        ]);

        $fcm_token = User::where('id', Auth::user()->pengamat_id)->first()->fcm_token;
        $title = 'Pengajuan Dinas Luar';
        $message = Auth::user()->name . ' Mengajukan Dinas Luar dengan tujuan ' . $request->tujuan;
        if ($fcm_token != null) {
            $this->fcmService->sendNotification($fcm_token, $title, $message);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Dinas Luar berhasil diajukan'
        ]);
    }


    public function getDinasLuar()
    {
        $user = Auth::user();
        if ($user->role == 'pekerja' || $user->role == 'mandor') {
            $data = DinasLuar::where('user_id', Auth::user()->id)
                ->where('tanggal_mulai', '>=', now()->subMonths(2))
                ->get();
        } else {
            $pengamat_id = Auth::user()->id;
            $data = DinasLuar::whereHas('user', function ($query) use ($pengamat_id) {
                $query->where('pengamat_id', $pengamat_id);
            })->where('tanggal_mulai', '>=', now()->subMonths(2))->with('user')->get();
        }



        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function approvalDinasLuar(Request $request)
    {

        $dinasLuar = DinasLuar::find($request->id);
        $fcm_token = User::where('id', $dinasLuar->user_id)->first()->fcm_token;
        $title = 'Pengajuan Dinas Luar';
        if ($request->status == 'Ditolak') {
            $dinasLuar->status = 'Ditolak';
            $message = 'Dinas Luar Ditolak dengan tujuan ' . $dinasLuar->tujuan;
        } else {
            $dinasLuar->status = 'Disetujui';
            $message = 'Dinas Luar Disetujui dengan tujuan ' . $dinasLuar->tujuan;

            $start = strtotime($dinasLuar->tanggal_mulai);
            $end = strtotime($dinasLuar->tanggal_selesai);
            $diff = $end - $start;
            $days = (int) floor($diff / (60 * 60 * 24));

            for ($i = 0; $i <= $days; $i++) {
                Absensi::updateOrCreate([
                    'user_id' => $dinasLuar->user_id,
                    'tanggal' => date('Y-m-d', strtotime($dinasLuar->tanggal_mulai . ' + ' . $i . ' days'))
                ], [
                    'keterangan' => $dinasLuar->type_dl . ' - ' . $dinasLuar->tujuan . ' - ' . $dinasLuar->kegiatan,
                    'dinas_luar_id' => $dinasLuar->id,
                ]);
            }
        }

        $dinasLuar->keterangan_status = $request->keterangan_status;
        $dinasLuar->save();
        if ($fcm_token != null) {
            $this->fcmService->sendNotification($fcm_token, $title, $message);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Dinas Luar berhasil disetujui'
        ]);
    }

    public function deleteDinasLuar($id)
    {
        try {
            $dinasLuar = DinasLuar::find($id);
            $dinasLuar->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Dinas Luar berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
