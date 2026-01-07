<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'device_id' => 'required',
            'password' => 'required',
            'fcm_token' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 401);
        }

        $regex = '/^[0-9]+$/';

        if (preg_match($regex, $request->email)) {
            $user = User::where('nik', $request->email)->first();
            if (!$user) {
                $user = User::where('nip', $request->email)->first();
            }
        } else {
            $user = User::where('email', $request->email)->first();
        }


        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan hubungi admin'
            ], 401);
        }

        $allowLogin = [0, 1, 2, 3, 4, 5];
        if (!in_array($user->id, $allowLogin)) {
            if ($request->version != "1.0.1") {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Versi aplikasi tidak sesuai, silahkan update aplikasi'
                ], 401);
            }
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        $allowLogin = [0, 1, 2, 3, 4, 5];
        if (!in_array($user->id, $allowLogin)) {
            if ($user->role == 'pekerja' || $user->role == 'mandor') {
                if ($user->device_id != null && $user->device_id != $request->device_id) {

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Device tidak sesuai hubungi admin'
                    ], 401);
                }
                $device_id = $request->device_id;
                $check = User::where('device_id', $device_id)->first();
                if ($check && $check->id != $user->id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Perangkat ini sudah digunakan oleh ' . $check->name
                    ], 401);
                }
            }

            if ($user->deleted_at) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User di SUSPEND harap hubungi admin'
                ], 401);
            }
        }

        $user->device_id = $request->device_id;
        $user->fcm_token = $request->fcm_token;
        $user->save();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $file = $request->file('foto');
        if ($file) {
            $nama_file = time() . "_" . $file->getClientOriginalName();
            Storage::putFileAs('public/avatar/', $file, $nama_file);
            $request->merge([
                'avatar' => $nama_file
            ]);
        }
        $ktp = $request->file('ktp');
        if ($ktp) {
            $nama_file = time() . "_" . $ktp->getClientOriginalName();
            Storage::putFileAs('public/ktp/', $ktp, $nama_file);
            $request->merge([
                'identity_photo' => $nama_file
            ]);
        }
        $request->user()->update($request->all());


        return response()->json([
            'message' => 'Profile updated'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => 'required',
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 401);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password updated'
        ]);
    }

    public function me(Request $request)
    {
        $type_absensi = '';
        $user = $request->user();

        if ($user->absensi_today == null || $user->absensi_today->jam_masuk == null && $user->absensi_today->dinas_luar_id == null) {
            $type_absensi = "Masuk";
        } else if ($user->absensi_today->jam_keluar == null && $user->absensi_today->dinas_luar_id == null) {
            $type_absensi = "Keluar";
        } else {
            if ($user->dinas_luar_today != null) {
                $type_absensi =  $user->dinas_luar_today->type_dl;
            }
        }
        $user->type_absensi = $type_absensi;


        return response()->json([
            'message' => 'Success',
            'user' => $request->user(),

        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Success'
        ]);
    }

    public function cancelVerifikasi()
    {
        $user = User::find(Auth::id());
        $user->update([
            'no_hp' => null,
            'identity_photo' => null
        ]);

        return response()->json([
            'message' => 'Success'
        ]);
    }
}
