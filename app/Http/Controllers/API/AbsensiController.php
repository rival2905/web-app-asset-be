<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function absensi(Request $request)
    {

        $file = $request->file('foto');
        $nama_file = time() . "_" . $file->getClientOriginalName();

        $absensi = Absensi::where('user_id', Auth::user()->id)->where('tanggal', date("Y/m/d"))->latest()->first();


        if ($request->type == "Masuk" && !$absensi) {

            // if (Carbon::now()->greaterThanOrEqualTo(Carbon::parse(date("Y/m/d") . " 09:00:00"))) {
            //     return response()->json([
            //         'message' => 'Absen hanya bisa dilakukan sebelum jam 9 pagi'
            //     ], 403);
            // }

            Absensi::create([
                'user_id' => Auth::user()->id,
                'tanggal' => date("Y/m/d"),
                'jam_masuk' => date("H:i:s"),
                'lokasi_masuk' => $request->lokasi,
                'latitude_masuk' => $request->latitude,
                'longitude_masuk' => $request->longitude,
                'foto_masuk' =>   $nama_file,
                'keterangan' => \Carbon\Carbon::parse(date("H:i:s"))->greaterThan(\Carbon\Carbon::parse(Auth::user()->jam_masuk)) ? 'Terlambat' : 'Tepat Waktu'
            ]);
            Storage::putFileAs('public/foto_absensi/masuk/', $file, $nama_file);
            // User::where('id', Auth::user()->id)->update(['avatar' => $nama_file]);
            return response()->json([
                'message' => 'Absen masuk berhasil'
            ]);
        } else if ($absensi->dinas_luar_id != null && $absensi->jam_masuk == null) {
            // if (Carbon::now()->greaterThanOrEqualTo(Carbon::parse(date("Y/m/d") . " 09:00:00"))) {
            //     return response()->json([
            //         'message' => 'Absen hanya bisa dilakukan sebelum jam 9 pagi'
            //     ], 403);
            // }
            $absensi->update([
                'jam_masuk' => date("H:i:s"),
                'lokasi_masuk' => $request->lokasi,
                'latitude_masuk' => $request->latitude,
                'longitude_masuk' => $request->longitude,
                'foto_masuk' => $nama_file,
            ]);

            return response()->json([
                'message' => 'Absen masuk berhasil'
            ]);
        } else if (\Carbon\Carbon::parse(date("H:i:s"))->greaterThan(\Carbon\Carbon::parse(Auth::user()->jam_keluar))) {
            $absensi->update([
                'jam_keluar' => date("H:i:s"),
                'lokasi_keluar' => $request->lokasi,
                'latitude_keluar' => $request->latitude,
                'longitude_keluar' => $request->longitude,
                'foto_keluar' => $nama_file,
            ]);
            Storage::putFileAs('public/foto_absensi/keluar/', $file, $nama_file);

            return response()->json([
                'message' => 'Absen pulang berhasil'
            ]);
        }

        return response()->json([
            'message' => 'Absen gagal'
        ], 404);
    }

    public function absensiFromAdmin(Request $request, $id)
    {
        $user = User::find($id);
        $absensi = Absensi::where('user_id', $id)->where('tanggal', date("Y/m/d"))->latest()->first();
        $file = $request->file('foto');
        $nama_file = time() . "_" . $file->getClientOriginalName();
        if (!$absensi) {
            Absensi::create([
                'user_id' => $id,
                'tanggal' => date("Y/m/d"),
                'jam_masuk' => date("H:i:s"),
                'lokasi_masuk' => "Absen Oleh " . Auth::user()->name,
                'latitude_masuk' => $user->lokasi_kerja->first()->latitude ?? null,
                'longitude_masuk' => $user->lokasi_kerja->first()->longitude ?? null,
                'foto_masuk' => $nama_file,
                'keterangan' => "Absen Oleh " . Auth::user()->name . " : " . $request->keterangan,
            ]);
            Storage::putFileAs('public/foto_absensi/masuk/', $file, $nama_file);
            return response()->json([
                'message' => 'Absen masuk berhasil'
            ]);
        } else if ($absensi->jam_masuk == null) {
            $absensi->update([
                'jam_masuk' => date("H:i:s"),
                'lokasi_masuk' => "Absen Oleh " . Auth::user()->name . " : " . $request->keterangan,
                'latitude_masuk' => $user->lokasi_kerja->first()->latitude ?? null,
                'longitude_masuk' => $user->lokasi_kerja->first()->longitude ?? null,
                'foto_masuk' => $nama_file,
            ]);
            Storage::putFileAs('public/foto_absensi/masuk/', $file, $nama_file);
            return response()->json([
                'message' => 'Absen masuk berhasil'
            ]);
        } else {
            $absensi->update([
                'jam_keluar' => date("H:i:s"),
                'lokasi_keluar' => "Absen Oleh " . Auth::user()->name . " : " . $request->keterangan,
                'latitude_keluar' => $user->lokasi_kerja->first()->latitude ?? null,
                'longitude_keluar' => $user->lokasi_kerja->first()->longitude ?? null,
            ]);
            Storage::putFileAs('public/foto_absensi/keluar/', $file, $nama_file);
            return response()->json([
                'message' => 'Absen pulang berhasil'
            ]);
        }
    }

    public function fixsensi(Request $request, $id)
    {

        $file = $request->file('image');
        $nama_file = time() . "_" . $file->getClientOriginalName();

        $absensi = Absensi::where('user_id', $request->user_id)->where('tanggal', $request->tanggal)->latest()->first();


        if ($request->type == "Masuk" && !$absensi) {

            // if (Carbon::now()->greaterThanOrEqualTo(Carbon::parse($request->tanggal . " 09:00:00"))) {
            //     return response()->json([
            //         'message' => 'Absen hanya bisa dilakukan sebelum jam 9 pagi'
            //     ], 403);
            // }

            Absensi::create([
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
                'jam_masuk' => $request->jam,
                'lokasi_masuk' => $request->lokasi,
                'latitude_masuk' => $request->latitude,
                'longitude_masuk' => $request->longitude,
                'foto_masuk' =>   $nama_file,
                'keterangan' => \Carbon\Carbon::parse($request->jam)->greaterThan(\Carbon\Carbon::parse(Auth::user()->jam_masuk)) ? 'Terlambat' : 'Tepat Waktu'
            ]);
            Storage::putFileAs('public/foto_absensi/masuk/', $file, $nama_file);
            // User::where('id', Auth::user()->id)->update(['avatar' => $nama_file]);
            return response()->json([
                'message' => 'Absen masuk berhasil'
            ]);
        } else if ($absensi->dinas_luar_id != null && $absensi->jam_masuk == null) {
            // if (Carbon::now()->greaterThanOrEqualTo(Carbon::parse(date("Y/m/d") . " 09:00:00"))) {
            //     return response()->json([
            //         'message' => 'Absen hanya bisa dilakukan sebelum jam 9 pagi'
            //     ], 403);
            // }
            $absensi->update([
                'jam_masuk' => $request->jam,
                'lokasi_masuk' => $request->lokasi,
                'latitude_masuk' => $request->latitude,
                'longitude_masuk' => $request->longitude,
                'foto_masuk' => $nama_file,
            ]);

            return response()->json([
                'message' => 'Absen masuk berhasil'
            ]);
        } else if (\Carbon\Carbon::parse($request->jam)->greaterThan(\Carbon\Carbon::parse(Auth::user()->jam_keluar))) {
            $absensi->update([
                'jam_keluar' => $request->jam,
                'lokasi_keluar' => $request->lokasi,
                'latitude_keluar' => $request->latitude,
                'longitude_keluar' => $request->longitude,
                'foto_keluar' => $nama_file,
            ]);
            Storage::putFileAs('public/foto_absensi/keluar/', $file, $nama_file);

            return response()->json([
                'message' => 'Absen pulang berhasil'
            ]);
        }

        return response()->json([
            'message' => 'Absen gagal'
        ], 404);
    }
}
