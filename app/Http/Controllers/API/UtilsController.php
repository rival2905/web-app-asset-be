<?php

namespace App\Http\Controllers\API;



use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Logs;
use App\Models\MasterLokasiKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Services\FcmService;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UtilsController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }



    public function progressBulanan()
    {
        $user = Auth::user();
        $absensiBulanIni = Absensi::where('user_id', $user->id)->where('tanggal', 'like', date('Y-m') . '%')->get();
        $absensiBulanLalu = Absensi::where('user_id', $user->id)->where('tanggal', 'like', date('Y-m', strtotime('-1 month')) . '%')->get();
        $countDateThisMonth = date('t', strtotime(date('Y-m') . '-01'));
        $countDateLastMonth = date('t', strtotime(date('Y-m', strtotime('-1 month')) . '-01'));
        $dateThisMonth = [];
        $dateLastMonth = [];
        for ($i = 1; $i <= $countDateThisMonth; $i++) {
            $date =  date('Y-m') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $time = strtotime($date);
            $day = date('l', $time);
            if ($day != 'Sunday') {
                $dateThisMonth[] = date('Y-m') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            }
        }
        for ($i = 1; $i <= $countDateLastMonth; $i++) {
            $date = date('Y-m', strtotime('-1 month')) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $time = strtotime($date);

            $day = date('l', $time);
            if ($day != 'Sunday') {
                $dateLastMonth[] = date('Y-m', strtotime('-1 month')) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            }
        }
        $alphaThisMonth = 0;
        $alphaLastMonth = 0;
        $masukThisMonth = 0;
        $masukThisMonthInMinutes = 0;
        $masukLastMonth = 0;
        $masukLastMonthInMinutes = 0;
        $izinThisMonth = 0;
        $izinLastMonth = 0;
        $dinasLuarThisMonth = 0;
        $dinasLuarLastMonth = 0;
        $terlambatThisMonth = 0;
        $terlambatLastMonth = 0;
        $terlambatThisMonthInMinutes = 0;
        $terlambatLastMonthInMinutes = 0;
        $alphaThisMonthInMinutes = 0;
        $alphaLastMonthInMinutes = 0;

        foreach ($absensiBulanIni as $key => $value) {
            if (str_contains($value->keterangan, 'Dinas') && $value->jam_masuk != null) {
                $dinasLuarThisMonth++;
                //                $masukThisMonth++;
            } else if ($value->jam_masuk != null && \Carbon\Carbon::parse($value->jam_masuk)->greaterThan(\Carbon\Carbon::parse($user->jam_masuk))) {
                $masukThisMonth++;
                $terlambatThisMonth++;
                $terlambatThisMonthInMinutes += \Carbon\Carbon::parse($value->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($user->jam_masuk));
            } else if (str_contains($value->keterangan, 'Izin')) {
                $izinThisMonth++;
            } else if ($value->jam_masuk == null && !str_contains($value->keterangan, 'Izin')) {
                $alphaThisMonth++;
                $alphaThisMonthInMinutes += 8 * 60;
            } else if ($value->jam_masuk != null) {
                $masukThisMonthInMinutes += \Carbon\Carbon::parse($value->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($value->jam_keluar ?? '16:00:00'));
                $masukThisMonth++;
            }
        }
        foreach ($absensiBulanLalu as $key => $value) {
            if (str_contains($value->keterangan, 'Dinas') && $value->jam_masuk != null) {
                $dinasLuarLastMonth++;
                //                $masukLastMonth++;
            } else if ($value->jam_masuk != null && \Carbon\Carbon::parse($value->jam_masuk)->greaterThan(\Carbon\Carbon::parse($user->jam_masuk))) {
                $masukLastMonth++;
                $terlambatLastMonth++;
                $terlambatLastMonthInMinutes += \Carbon\Carbon::parse($value->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($user->jam_masuk));
            } else if (str_contains($value->keterangan, 'Izin')) {
                $izinLastMonth++;
            } else if ($value->jam_masuk == null && !str_contains($value->keterangan, 'Izin')) {
                $alphaLastMonth++;
                $alphaLastMonthInMinutes += 8 * 60;
            } else if ($value->jam_masuk != null) {
                $masukLastMonthInMinutes += \Carbon\Carbon::parse($value->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($value->jam_keluar ?? '16:00:00'));
                $masukLastMonth++;
            }
        }
        //filter dateThisMonth <= date('Y-m-d')

        // 
        $alphaThisMonth += count(array_filter($dateThisMonth, function ($date) {
            return $date <= date('Y-m-d');
        })) - $masukThisMonth - $izinThisMonth - $dinasLuarThisMonth;

        //sync date this month with absensi push to date this mount if not exist
        // check date on absensiBulanIni if not exist push to date this month
        foreach ($absensiBulanIni as $key => $value) {

            if (!in_array($value->tanggal, $dateThisMonth)) {
                array_push($dateThisMonth, $value->tanggal);
            }
        }
        sort($dateThisMonth);
        foreach ($absensiBulanLalu as $key => $value) {

            if (!in_array($value->tanggal, $dateLastMonth)) {
                array_push($dateLastMonth, $value->tanggal);
            }
        }
        sort($dateLastMonth);
        $thisMonth = [
            "countDate" => count($dateThisMonth),
            "countAbsensi" => count($absensiBulanIni),
            "totalMinutes" => 8 * count($dateThisMonth) * 60,
            "masuk" => $masukThisMonth,
            "masukThisMonthInMinutes" => $masukThisMonthInMinutes,
            "izin" => $izinThisMonth,
            "alpha" => $alphaThisMonth <= 0 ? 0 : $alphaThisMonth,
            "dinasLuar" => $dinasLuarThisMonth,
            "terlambat" => $terlambatThisMonth,
            "terlambatInMinutes" => $terlambatThisMonthInMinutes,
            "alphaInMinutes" => $alphaThisMonth <= 0 ? 0 : $alphaThisMonthInMinutes += 8 * 60 * $alphaThisMonth,
            "dateThisMonth" => $dateThisMonth,
            "dataAbsensi" => $absensiBulanIni,


        ];
        $alphaLastMonth += count($dateLastMonth) - $masukLastMonth - $izinLastMonth - $dinasLuarLastMonth;
        $lastMonth = [
            "countDate" => count($dateLastMonth),
            "countAbsensi" => count($absensiBulanLalu),
            "totalMinutes" => 8 * count($dateLastMonth) * 60,
            "masuk" => $masukLastMonth,
            "masukThisMonthInMinutes" => $masukLastMonthInMinutes,
            "izin" => $izinLastMonth,
            "alpha" => $alphaLastMonth <= 0 ? 0 : $alphaLastMonth,
            "dinasLuar" => $dinasLuarLastMonth,
            "terlambat" => $terlambatLastMonth,
            "terlambatInMinutes" => $terlambatLastMonthInMinutes,
            "alphaInMinutes" => $alphaLastMonth <= 0 ? 0 : $alphaLastMonth += 8 * 60 * $alphaLastMonth,
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'thisMonth' => $thisMonth,
                'lastMonth' => $lastMonth,
            ]
        ], 200);
    }


    public function pushNotif(Request $req)
    {
        $fcm_token = "dFHte036Q1eMLiY11nBHUp:APA91bHHuRFMS4MyQ5TDuAo3IUZw7ZS_4albLEjxssh8sh-Rowsa0ahM8sqE_ES0MA5qUrULHVIijuPlBpLgvAld0xEz4dJtuuB2kA_fRnRx8rWARvbQT0c";
        $title = 'Pengajuan Dinas Luar';
        $message = 'Dinas Luar Baru Diterima dengan Nomor Tiket ';

        $this->fcmService->sendNotification($fcm_token, $title, $message);

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi berhasil dikirim.'
        ], 200);
    }
    public function errorLogs(Request $request)
    {

        $log = Logs::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'type_absen' => $request->type_absen,
            'lat_user' => $request->lat_user,
            'long_user' => $request->long_user,
            'lat_absen' => $request->lat_absen,
            'long_absen' => $request->long_absen,
            'distance' => $request->distance,
            'type' => $request->type,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $log,
        ], 200);
    }

    public function resetUser($id)
    {


        $user = User::find($id);
        if ($user->reset_count >= 5) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah mencapai batas maksimal reset device.',
            ], 403);
        }
        $user->reset_count = $user->reset_count + 1;
        $user->update([
            'device_id' => null,
            'fcm_token' => null,
        ]);
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'device berhasil direset.',
        ], 200);
    }

    public function getPekerja()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role == 'admin') {
            $data = User::whereIn('role', ['pekerja', 'mandor'])->get();
        } elseif ($user->role == 'mandor') {
            $data = User::where('mandor_id', $user->id)
                ->whereNull('deleted_at')
                ->get();
        } elseif ($user->role == 'pengamat') {
            $data = User::where('pengamat_id', $user->id)
                ->whereIn('role', ['mandor', 'pekerja'])
                ->whereNull('deleted_at')
                ->get();
        } elseif ($user->role == 'ksppj') {
            $data = User::where('ksppj_id', $user->id)
                ->whereIn('role', ['mandor', 'pekerja'])
                ->whereNull('deleted_at')
                ->get();
        } elseif ($user->role == 'subkoor' || $user->role == 'kuptd') {
            $data = User::where('uptd_id', $user->uptd_id)
                ->whereIn('role', ['mandor', 'pekerja'])
                ->whereNull('deleted_at')
                ->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
    public function rekapAbsen($id, $periode)
    {
        $dataDb = Absensi::where('user_id', $id)->where('tanggal', 'like', $periode . '%')->with('dinas_luar', 'izin')->get();

        $countDateThisMonth = date('t', strtotime($periode . '-01'));
        $dateThisMonth = [];
        for ($i = 1; $i <= $countDateThisMonth; $i++) {
            $date =  date('Y-m') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $time = strtotime($date);
            $day = date('l', $time);


            if ($day != 'Sunday') {
                $dateThisMonth[] = date('Y-m') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            }
        }

        $dateThisMonth = array_values($dateThisMonth);
        $dinasLuar = 0;
        $masuk = 0;
        $izin = 0;
        $alpha = 0;
        $terlambat = 0;


        foreach ($dataDb as $key => $value) {
            if (str_contains($value->keterangan, 'Dinas') && $value->jam_masuk != null) {
                $dinasLuar++;
                $masuk++;
            } else if ($value->jam_masuk != null && \Carbon\Carbon::parse($value->jam_masuk)->greaterThan(\Carbon\Carbon::parse($value->user->jam_masuk))) {
                $masuk++;
                $terlambat++;
            } else if (str_contains($value->keterangan, 'Izin')) {
                $izin++;
            } else if ($value->jam_masuk == null && !str_contains($value->keterangan, 'Izin')) {
                $alpha++;
            } else if ($value->jam_masuk != null) {
                $masuk++;
            }

            if (str_contains($value->lokasi_masuk, "Absen")) {
                $value->keterangan = $value->keterangan . " " . $value->lokasi_masuk;
            }
        }

        $data = [
            'masuk' => $masuk,
            'izin' => $izin,
            'alpha' => $alpha > 0 ? $alpha : 0,
            'dinas_luar' => $dinasLuar,
            'terlambat' => $terlambat,
            'totalThisMonth' => count($dateThisMonth),
            'data' => $dataDb,
            'periode' => $periode
        ];
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function getMasterLokasiKerja()
    {

        return response()->json([
            'status' => 'success',
            'data' => MasterLokasiKerja::all(),
        ], 200);
    }

    public function getNearbyPoint(Request $request)
    {
        try {

            $user = Auth::user();
            $idRuas = $user->lokasi_kerja
                ->where('ruas_jalan_id', '!=', null)
                ->pluck('ruas_jalan_id')
                ->toArray();
            //convert to array string
            $idRuas = array_map('strval', $idRuas);

            if (empty($idRuas)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses ke ruas jalan manapun.',
                ], 403);
            }


            $request->validate([
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
            ]);
            $userLat = $request->query('lat');
            $userLng = $request->query('lng');


            $geoServerUrl = "https://geo.temanjabar.net/geoserver/wfs";

            $idRuas = array_map(function ($id) {
                return "'$id'";
            }, $idRuas);
            $response = Http::get($geoServerUrl, [
                'service' => 'WFS',
                'version' => '2.0.0',
                'request' => 'GetFeature',
                'typeName' => 'temanjabar:0_rj_prov',
                'CQL_FILTER' => "idruas IN (" . implode(",", $idRuas) . ")",
                'outputFormat' => 'application/json',
                'srsName' => 'EPSG:4326',
            ]);



            if (!$response->ok() || empty($response->json())) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mendapatkan data dari GeoServer.',
                ], 500);
            }

            $data = $response->json();


            if (!isset($data['features']) || count($data['features']) === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada fitur ditemukan untuk ID ruas jalan yang diberikan.',
                ], 404);
            }


            $nearestPoint = [];
            foreach ($data['features'] as $feature) {
                $polylines = $feature['geometry']['coordinates'];

                $temp = null;
                foreach ($polylines as $polyline) {
                    $result = $this->findNearestPoint($polyline, $userLat, $userLng);
                    if ($temp == null || $temp['distance'] > $result['distance']) {
                        $temp = $result;
                    }
                }

                array_push($nearestPoint, [
                    'ruasJalan' => $feature['properties']['nm_ruas'],
                    'kodeRuas' => $feature['properties']['idruas'],
                    'nearestPoint' => $temp['nearestPoint'],
                    'distance' => $temp['distance'],
                ]);
            }
            //remove duplicate and sort by distance
            $nearestPoint = collect($nearestPoint)
                ->unique('kodeRuas')
                ->sortBy('distance')
                ->values()
                ->all();


            return response()->json([
                'status' => 'success',
                'data' => $nearestPoint,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000;


        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);


        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        $a = sin($dLat / 2) ** 2 +
            cos($lat1) * cos($lat2) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));


        return $earthRadius * $c;
    }

    private function findNearestPoint($polyline, $userLat, $userLng)
    {
        $minDistance = null;
        $nearestPoint = null;

        foreach ($polyline as $point) {
            [$lng, $lat] = $point;
            $distance = $this->haversine($userLat, $userLng, $lat, $lng);

            if ($minDistance === null || $distance < $minDistance) {
                $minDistance = $distance;
                $nearestPoint = ['lat' => $lat, 'lng' => $lng];
            }
        }

        return [
            'nearestPoint' => $nearestPoint,
            'distance' => $minDistance,
        ];
    }

    public function sendNotif($id)
    {
        $user = User::find($id);
        $fcm_token = $user->fcm_token;
        $title = 'Segera Lakukan Absensi';
        $message = 'Segera melakukan absensi hari ini. Notifikasi ini dikirimkan oleh ' . Auth::user()->name . '.';

        $this->fcmService->sendNotification($fcm_token, $title, $message);

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi berhasil dikirim.'
        ], 200);
    }
}
