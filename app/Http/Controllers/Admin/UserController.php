<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use App\Models\User;
use App\Models\UserTemp;
use App\Models\MasterUnit;

use App\Models\MasterLokasiKerja;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $units = MasterUnit::orderByRaw('RAND()')->get();
        $users = User::wherenull('deleted_at')->latest()->when(request()->q, function($users) {
            $users = $users->where('name', 'like', '%'. request()->q . '%');
        });
        $temp_pekerja['verified'] = User::whereIn('role',['pekerja','mandor'])->whereNotNull('account_verified_at')->whereNull('deleted_at');
        $temp_pekerja['unverified']= User::whereIn('role',['pekerja','mandor'])->whereNull('account_verified_at')->whereNull('deleted_at');

        if(Auth::user()->unit_id){
            $filter['unit_id'] = Auth::user()->unit_id;
        }else if($request->unit_id){ 
            $filter['unit_id'] = $request->unit_id;
        }
        
        $temp_pekerja['verified'] = $temp_pekerja['verified']->count();
        $temp_pekerja['unverified'] = $temp_pekerja['unverified']->count();

        $users = $users->get();

        // dd($filter);
        return view('admin.user.index', compact('users','temp_pekerja','units'));

    }

    /**
     * Display a listing of the resource.
     */
    public function restore(Request $request)
    {
        //
        
        $users = User::wherenotnull('deleted_at')->latest()->when(request()->q, function($users) {
            $users = $users->where('name', 'like', '%'. request()->q . '%');
        });

        $users = $users->get();

        return view('admin.user.restore', compact('users'));

    }

    public function export(Request $request)
    {
        //
        
        $users = User::latest()->whereIn('role',['pekerja','mandor'])->when(request()->q, function($users) {
            $users = $users->where('name', 'like', '%'. request()->q . '%');
        });
       

        $user_ksppj = User::where('role','ksppjj'); 
        if(Auth::user()->uptd_id){
            $uptds = array(Auth::user()->uptd_id);
        }else{
            $uptds = array(1, 2, 3, 4, 5, 6);
        }
        if($request->uptd_id){ 
            $filter['uptd_id'] = $request->uptd_id;
        }else{
            shuffle($uptds);
            $filter['uptd_id'] = $uptds[0];
        }
        

        $users = $users->where('uptd_id',$filter['uptd_id'])->get();
        $user_ksppj = $user_ksppj->where('uptd_id',$filter['uptd_id'])->get();

        // dd($filter);
        return view('admin.user.export', compact('users','uptds','filter','user_ksppj'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $action = "store";

        $units = MasterUnit::get();
        
        $jabatans = User::select('jabatan')->orderBy('jabatan')->groupBy('jabatan');
        $jabatans = $jabatans->get();
        
        return view('admin.user.form', compact('action','units','jabatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed',
            'avatar'     => 'image|mimes:png,jpg,webp,jpeg,svg|max:2048',
            'identity_photo'     => 'image|mimes:png,jpg,webp,jpeg,svg|max:2048',
            'nik'   => '',
            'nip'  => '',
            'jabatan'  => '',
            'unit'  => '',
        ]);
        $data = [
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'nik'     => $request->input('nik'),
            'nip'     => $request->input('nip'),
            'jabatan'     => $request->input('jabatan'),
            'password'  => Hash::make($request->input('password')),
        ];
        
        if($request->input('jabatan') == "Admin"){
            $data['role'] = "admin";
        }else if($request->input('jabatan') == "Kepala UPTD"){
            $data['role'] = "kuptd";
        }else if($request->input('jabatan') == "Sub Koor"){
            $data['role'] = "subkoor";
        }else if($request->input('jabatan') == "KSPPJ"){
            $data['role'] = "ksppj";
        }else if($request->input('jabatan') == "Pegawai"){
            $data['role'] = "pegawai";
        }else if($request->input('jabatan') == "Penanggung Jawab"){
            $data['role'] = "penanggung-jawab";
        }else{
            return redirect()->route('admin.user.index')->with(['error' => 'Hubungi admin pusat untuk perubahan data tersebut!!']);
        } 
        
        $data_unit = MasterUnit::find($request->input('unit'));
        if($data_unit->id){
            $data['bidang'] = $data_unit->name;
            $data['unit_id'] = $data_unit->id;
            $data['uptd_id'] = $data_unit->uptd_id;
        }
       
     
        if($request->file('avatar')) {
            //upload avatar
            $avatar = $request->file('avatar');
            $name = $avatar->hashName();
            $path = 'avatar/' .$name;
            
            $avatar->storeAs('public/', $path);
            
            $data['avatar'] = $name;
        }

        if($request->file('identity_photo')) {
            //upload identity_photo
            $identity_photo = $request->file('identity_photo');
            $name = $identity_photo->hashName();
            $path = 'ktp/' .$name;
            
            $identity_photo->storeAs('public/', $path);
            
            $data['identity_photo'] = $name;
        }

        $user = User::create($data);

        if($user){
            //redirect dengan pesan sukses
            return redirect()->route('admin.user.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.user.index')->with(['error' => 'Data Gagal Disimpan!']);
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
    public function edit(string $id)
    {
        //
        try {
            $id = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            return back()->with('error', 'Terjadi kesalahan ketika mengakses halaman!!')->withInput();
        }

        $action = "update";

        $data = User::find($id);
        $units = MasterUnit::get();
        
        $jabatans = User::select('jabatan')->orderBy('jabatan')->groupBy('jabatan');
        // if(Auth::user()->id != 0 && Auth::user()->jabatan){
        //     $jabatans = $jabatans->where('jabatan',Auth::user()->jabatan);
        // }
        $jabatans = $jabatans->get();

        return view('admin.user.form', compact('action','units','data','jabatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        //
        $user = User::findOrFail($id);
        // dd($user);

        //validator
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'password'  => 'confirmed',
            'avatar'     => 'image|mimes:png,jpg,webp,jpeg,svg|max:2048',
            'identity_photo'     => 'image|mimes:png,jpg,webp,jpeg,svg|max:2048',
            'nik'   => '',
            'nip'  => '',
            'jabatan'  => '',
            'unit'  => '',
        ]);
        $data = [
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'nik'     => $request->input('nik'),
            'nip'     => $request->input('nip'),
            'jabatan'     => $request->input('jabatan'),

        ];
        
        if($request->input('jabatan') == "Admin"){
            $data['role'] = "admin";
        }else if($request->input('jabatan') == "Kepala UPTD"){
            $data['role'] = "kuptd";
        }else if($request->input('jabatan') == "Sub Koor"){
            $data['role'] = "subkoor";
        }else if($request->input('jabatan') == "KSPPJ"){
            $data['role'] = "ksppj";
        }else if($request->input('jabatan') == "Pegawai"){
            $data['role'] = "pegawai";
        }else if($request->input('jabatan') == "Penanggung Jawab"){
            $data['role'] = "penanggung-jawab";
        }else{
            return redirect()->route('admin.user.index')->with(['error' => 'Hubungi admin pusat untuk perubahan data tersebut!!']);
        } 

        $data_unit = MasterUnit::find($request->input('unit'));
        if($data_unit->id){
            $data['bidang'] = $data_unit->name;
            $data['unit_id'] = $data_unit->id;
            $data['uptd_id'] = $data_unit->uptd_id;
        }

        // dd($data);
        if($request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        } 

        if($request->file('avatar')) {
            //upload avatar
            if($user->avatar){
                //remove old image
                Storage::disk('local')->delete('public/users/'.$user->avatar);
            }

            $avatar = $request->file('avatar');
            $name = $avatar->hashName();
            $path = 'avatar/' .$name;
            
            $avatar->storeAs('public/', $path);
            
            $data['avatar'] = $name;
        }

        if($request->file('identity_photo')) {
            //upload identity_photo
            if($user->identity_photo){
                //remove old image
                Storage::disk('local')->delete('public/ktp/'.$user->identity_photo);
            }

            $identity_photo = $request->file('identity_photo');
            $name = $identity_photo->hashName();
            $path = 'ktp/' .$name;
            
            $data['identity_photo'] = $name;
            $identity_photo->storeAs('public/', $path);
            
        }
        
        $user->update($data);

        if($user){
            return redirect()->route('admin.user.index')->with(['success' => 'Data Berhasil Diupdate!']);
            
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.user.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function myprofile()
    {
        $data = Auth::user();
        return view('admin.user.my-profile', compact('data'));
    }
    public function myprofileUpdate(Request $request)
    {
        //
        $user = Auth::user();
        // dd($user);
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'password'  => 'confirmed',
            'avatar'     => 'image|mimes:png,jpg,webp,jpeg,svg|max:2048',
            'identity_photo'     => 'image|mimes:png,jpg,webp,jpeg,svg|max:2048',
            'nik'   => '',
            'nip'  => '',
        ]);
        $data = [
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'nik'     => $request->input('nik'),
            'nip'     => $request->input('nip'),
        ];
        
        if($request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        } 

        if($request->file('avatar')) {
            //upload avatar
            if($user->avatar){
                //remove old image
                Storage::disk('local')->delete('public/users/'.$user->avatar);
            }

            $avatar = $request->file('avatar');
            $name = $avatar->hashName();
            $path = 'avatar/' .$name;
            
            $avatar->storeAs('public/', $path);
            
            $data['avatar'] = $name;
        }

        if($request->file('identity_photo')) {
            //upload identity_photo
            if($user->identity_photo){
                //remove old image
                Storage::disk('local')->delete('public/ktp/'.$user->identity_photo);
            }

            $identity_photo = $request->file('identity_photo');
            $name = $identity_photo->hashName();
            $path = 'ktp/' .$name;
            
            $data['identity_photo'] = $name;
            $identity_photo->storeAs('public/', $path);
            
        }
        
        $user->update($data);

        if($user){
            return redirect()->route('user.myprofile')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('user.myprofile')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function verified(Request $request, $id)
    {
        //
        //
        $user = User::findOrFail($id);
        // dd($user);
        $this->validate($request, [
            'verified'      => '',
        ]);
        // dd($data);
        $user->account_verified_at = Carbon::now()->toDateTimeString();
        $user->save();
        
        if($user){
            //redirect dengan pesan sukses
            return back()->with(['success' => 'Data Pegawai Berhasil Di Verifikasi!']);
            // return redirect()->route('admin.user.index')->with(['success' => 'Data Pegawai Berhasil Di Verifikasi!']);

        }else{
            //redirect dengan pesan error
            // dd($user);

            return redirect()->route('admin.user.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $user = User::findOrFail($id);
        if($user->avatar){
            //remove old image
            // Storage::disk('local')->delete('public/avatar/'.$user->avatar);
        }
        if($user->identity_photo){
            Storage::disk('local')->delete('public/ktp/'.$user->identity_photo);
        }
        $user->delete();

        if($user){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
    public function soft_destroy($id)
    {
        //
        
        $data = DB::table('users')->where('id',$id)->first();
        $data = get_object_vars($data);
        $data['user_id'] = $data['id'];
        $data['deleted_by'] = Auth::user()->id;
        UserTemp::create($data);

        $user = User::findOrFail($id);
        $user->delete();

        if($user){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
    public function delete_two($id)
    {
        //
        $user = User::findOrFail($id);
        $user->deleted_at = Carbon::now()->toDateTimeString();
        $user->deleted_by = Auth::user()->id;
        $user->save();

        if($user){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
    public function restore_two($id)
    {
        //
        $user = User::findOrFail($id);
        $user->deleted_at = null;
        $user->deleted_by = null;
        $user->restored_by = Auth::user()->id;

        $user->save();

        if($user){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
    public function reset($id)
    {
        //
        // $user = User::findOrFail($id);
        // $user->device_id = null;
        // $user->save();

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


        if($user){
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
