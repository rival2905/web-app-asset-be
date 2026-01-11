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

use App\Models\MasterLokasiKerja;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $uptds = array(1, 2, 3, 4, 5, 6);
        
        $users = User::wherenull('deleted_at')->latest()->when(request()->q, function($users) {
            $users = $users->where('name', 'like', '%'. request()->q . '%');
        });
        $temp_pekerja['verified'] = User::whereIn('role',['pekerja','mandor'])->whereNotNull('account_verified_at')->whereNull('deleted_at');
        $temp_pekerja['unverified']= User::whereIn('role',['pekerja','mandor'])->whereNull('account_verified_at')->whereNull('deleted_at');

        $user_ksppj = User::where('role','ksppjj'); 
        if(Auth::user()->uptd_id){
            $uptds = array(Auth::user()->uptd_id);
            $filter['uptd_id'] = $uptds[0];
        }else if($request->uptd_id){ 
            $filter['uptd_id'] = $request->uptd_id;
        }else{
            shuffle($uptds);
            $filter['uptd_id'] = $uptds[0];
            $uptds = array(1, 2, 3, 4, 5, 6);

        }
        

        $is_subkoor = Auth::user()->role == 'subkoor';
        $is_ksppj = Auth::user()->role == 'ksppj';
        $is_pengamat = Auth::user()->role == 'pengamat';
        $is_mandor = Auth::user()->role == 'mandor';

        
        if($is_ksppj){
            $users = $users->where('ksppj_id',Auth::user()->id);
            $temp_pekerja['verified'] = $temp_pekerja['verified']->where('ksppj_id',Auth::user()->id);
            $temp_pekerja['unverified'] = $temp_pekerja['unverified']->where('ksppj_id',Auth::user()->id);
        }
        if($is_subkoor){
            $users = $users->where('subkoor',Auth::user()->id);
            $temp_pekerja['verified'] = $temp_pekerja['verified']->where('subkoor',Auth::user()->id);
            $temp_pekerja['unverified'] = $temp_pekerja['unverified']->where('subkoor',Auth::user()->id);
        }
        if($is_pengamat){
            $users = $users->where('pengamat_id',Auth::user()->id);
            $temp_pekerja['verified'] = $temp_pekerja['verified']->where('pengamat_id',Auth::user()->id);
            $temp_pekerja['unverified'] = $temp_pekerja['unverified']->where('pengamat_id',Auth::user()->id);
        }
        if($is_mandor){
            $users = $users->where('mandor_id',Auth::user()->id);  
            $temp_pekerja['verified'] = $temp_pekerja['verified']->where('mandor_id',Auth::user()->id);
            $temp_pekerja['unverified'] = $temp_pekerja['unverified']->where('mandor_id',Auth::user()->id);
        }

        $temp_pekerja['verified'] = $temp_pekerja['verified']->where('uptd_id',$filter['uptd_id'])->count();
        $temp_pekerja['unverified'] = $temp_pekerja['unverified']->where('uptd_id',$filter['uptd_id'])->count();

        $users = $users->where('uptd_id',$filter['uptd_id'])->get();
        $user_ksppj = $user_ksppj->where('uptd_id',$filter['uptd_id'])->get();

        // dd($filter);
        return view('admin.user.index', compact('users','uptds','filter','user_ksppj','temp_pekerja'));

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
            $uptds = array(1, 2, 3, 4, 5, 6);

        }

        $users = $users->where('uptd_id',$filter['uptd_id'])->get();

        // dd($filter);
        return view('admin.user.restore', compact('users','uptds','filter'));

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
        $positions = User::where('role','pengamat')->select('jabatan')->groupBy('jabatan')->pluck('jabatan')->toArray();
        if(Auth::user()->id != 0){
            $positions = array_diff($positions, array('Administrator'));
        }
        
        $fields = User::select('bidang')->orderBy('bidang')->groupBy('bidang');
        if(Auth::user()->id != 0 && Auth::user()->bidang){
            $fields = $fields->where('bidang',Auth::user()->bidang);
        }
        $fields = $fields->get();
        
        $locations = MasterLokasiKerja::latest();
        $data_pengamat = User::where('role','pengamat');
        if(Auth::user()->uptd_id){
            $locations = $locations->where('uptd_id',Auth::user()->uptd_id);
            $data_pengamat = $data_pengamat->where('uptd_id',Auth::user()->uptd_id);

            $uptds = array(Auth::user()->uptd_id);
        }else{
            $uptds = array(1, 2, 3, 4, 5, 6);
        }
        $jabatans = array('Pengelola Umum Operasional','Operator Layanan Operasional', 'Pengelola Layanan Operasional','Penata Layanan Operasional');

        $locations = $locations->get();
        $data_pengamat = $data_pengamat->get();

        return view('admin.user.form', compact('action','positions','fields','locations','data_pengamat', 'uptds','jabatans'));
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
            'bidang'  => '',
            'data_pengamat'  => '',
        ]);
        $data = [
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'nik'     => $request->input('nik'),
            'nip'     => $request->input('nip'),
            'jabatan'     => $request->input('jabatan'),
            'bidang'     => $request->input('bidang'),
            'password'  => Hash::make($request->input('password')),
            'uptd_id'   => $request->input('uptd_id'),
            'pengamat_id'   => $request->input('data_pengamat')

        ];
        $data_pengamat = User::find($request->input('data_pengamat'));
        if($data_pengamat->uptd_id){
            $data['uptd_id'] = $data_pengamat->uptd_id;
        }

        if($request->input('jabatan') == "Pengelola Umum Operasional"){
            $data['role'] = "pekerja";
        }else if($request->input('jabatan') == "Operator Layanan Operasional"){
            $data['role'] = "pekerja";
        }else if($request->input('jabatan') == "Pengelola Layanan Operasional"){
            $data['role'] = "pekerja";
        }else if($request->input('jabatan') == "Penata Layanan Operasional"){
            $data['role'] = "pekerja";
        }else{
            // $data['role'] = Str::lower($request->input('jabatan'));
            if(Auth::user()->id == 0 || Auth::user()->id == 3422){
                $data['role'] = $request->input('role');
            }else{
                return redirect()->route('admin.user.index')->with(['error' => 'Hubungi admin pusat untuk perubahan data tersebut!!']);
            }
        } 

        if($request->input('data_pengamat') == "Choose..."){
            $data['pengamat_id'] = null;
        }
        // dd($request->lokasi_kerja_id);
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
        $data['jam_masuk'] = "08:00:00";
        $data['jam_keluar'] = "16:00:00";

        $user = User::create($data);

        if($user){
            if($request->lokasi_kerja_id){
                $user->lokasi_kerja()->attach($request->lokasi_kerja_id);

            }
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

        $positions = User::select('jabatan')->groupBy('jabatan')->pluck('jabatan')->toArray();
        if(Auth::user()->id != 0){
            $positions = array_diff($positions, array('Administrator'));
        }
        
        $fields = User::select('bidang')->orderBy('bidang')->groupBy('bidang');
        if(Auth::user()->id != 0 && Auth::user()->bidang){
            $fields = $fields->where('bidang',Auth::user()->bidang);
        }
        $fields = $fields->get();

        $locations = MasterLokasiKerja::latest();
        $data_pengamat = User::where('role','pengamat');
        if(Auth::user()->uptd_id){
            $data_pengamat = $data_pengamat->where('uptd_id',Auth::user()->uptd_id);

            $uptds = array(Auth::user()->uptd_id);
        }else{
            $uptds = array(1, 2, 3, 4, 5, 6);
        }
        if($data->uptd_id){

            $data_pengamat = $data_pengamat->where('uptd_id',$data->uptd_id);
        }
        $locations = $locations->get();
        $data_pengamat = $data_pengamat->get();
        $jabatans = array('Pengelola Umum Operasional','Operator Layanan Operasional', 'Pengelola Layanan Operasional','Penata Layanan Operasional');

        return view('admin.user.form', compact('action','positions','fields','data','locations','data_pengamat','uptds','jabatans'));
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
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'password'  => 'confirmed',
            'avatar'     => 'image|mimes:png,jpg,webp,jpeg,svg|max:2048',
            'identity_photo'     => 'image|mimes:png,jpg,webp,jpeg,svg|max:2048',
            'nik'   => '',
            'nip'  => '',
            'jabatan'  => '',
            'bidang'  => '',
        ]);
        $data = [
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'nik'     => $request->input('nik'),
            'nip'     => $request->input('nip'),
            'jabatan'     => $request->input('jabatan'),
            'bidang'     => $request->input('bidang'),
            'uptd_id'   => $request->input('uptd_id'),
            'pengamat_id'   => $request->input('data_pengamat')

        ];
        $data_pengamat = User::find($request->input('data_pengamat'));
        if($data_pengamat->uptd_id){
            $data['uptd_id'] = $data_pengamat->uptd_id;
        }
        
        if($request->input('jabatan') == "Pengelola Umum Operasional"){
            $data['role'] = "pekerja";
        }else if($request->input('jabatan') == "Operator Layanan Operasional"){
            $data['role'] = "pekerja";
        }else if($request->input('jabatan') == "Pengelola Layanan Operasional"){
            $data['role'] = "pekerja";
        }else if($request->input('jabatan') == "Penata Layanan Operasional"){
            $data['role'] = "pekerja";
        }else{
            // $data['role'] = Str::lower($request->input('jabatan'));
            if(Auth::user()->id == 0 || Auth::user()->id == 3422){
                $data['role'] = $request->input('role');
            }else{
                return redirect()->route('admin.user.index')->with(['error' => 'Hubungi admin pusat untuk perubahan data tersebut!!']);
            }
        }
        if($request->input('data_pengamat') == "Choose..."){
            $data['pengamat_id'] = null;
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
          
            $user->lokasi_kerja()->sync($request->lokasi_kerja_id);
            if($data['uptd_id']){
                return redirect('/admin/user?uptd_id='.$data['uptd_id'])->with(['success' => 'Data Berhasil Diupdate!']);
                
            }else{
                //redirect dengan pesan sukses
                return redirect()->route('admin.user.index')->with(['success' => 'Data Berhasil Diupdate!']);

            }
            
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
        unset($data['id'],$data['lokasi_kerja'],$data['absensi_today'],$data['dinas_luar_today'],$data['izin_today'],$data['created_at'],$data['updated_at']);
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
