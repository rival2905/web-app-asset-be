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
        //
        $filter['tanggal_awal']= Carbon::now()->subDays(1)->format('Y-m-d');
        $filter['tanggal_akhir']= Carbon::now()->format('Y-m-d');
        // dd($filter);
        
        $user_check = User::whereIn('role',['pekerja','mandor']);

      
        if(Auth::user()->uptd_id){
            $uptds = array(Auth::user()->uptd_id);
            $daily['key_data'] = array('UPTD'.Auth::user()->uptd_id);
            // $user_check = $user_check->where('uptd_id', $filter['uptd_id']);

        }else{
            $uptds = array(1, 2, 3, 4, 5, 6);
            $daily['key_data'] = array('UPTD1','UPTD2','UPTD3','UPTD4','UPTD5','UPTD6');
        }
        if($request->uptd_id){ 
            $filter['uptd_id'] = $request->uptd_id;
            $daily['key_data'] = array('UPTD'.$request->uptd_id);
        }
        // dd(count($user_check));
        if($request->tanggal_akhir){ 
            $filter['tanggal_akhir'] = $request->tanggal_akhir;
        }
        // $absences = $absences->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$user_check)->get();
        for($i=0;$i< count($uptds);$i++){
        
            $data_user_check[$i] = User::whereIn('role',['pekerja','mandor'])->where('uptd_id', $uptds[$i])->pluck('id')->toArray();
        }
        // dd($data_user_check);
        for($i=0;$i< count($uptds);$i++){

            $presences = Absensi::whereNotNull('jam_masuk')->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$data_user_check[$i]);
            $absences = Absensi::whereNull('jam_masuk')->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$data_user_check[$i]);

            $daily['tepat_waktu'][] = $presences->where('keterangan','like','Tepat Waktu')->count();
            $daily['terlambat'][] = Absensi::whereNotNull('jam_masuk')->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$data_user_check[$i])->where('keterangan','Terlambat')->count();

            $daily['dinas_luar'][] = Absensi::whereNotNull('jam_masuk')->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$data_user_check[$i])->where('keterangan','like','%Dinas Luar Full%')->count();
            $daily['izin_sakit'][] = $absences->where('keterangan','like', 'Izin - Izin Sakit')->count();
            $daily['izin_lainnya'][] = Absensi::whereNull('jam_masuk')->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$data_user_check[$i])->where('keterangan','like', 'Izin - Izin Lainnya')->count();
            $daily['alpha'][] = (count($data_user_check[$i]) -  ($daily['tepat_waktu'][$i] + $daily['terlambat'][$i] + $daily['dinas_luar'][$i])) - $daily['izin_sakit'][$i] - $daily['izin_lainnya'][$i];
          
        }
       
        return view('admin.dashboard.index',compact('uptds','filter','user_check','daily'));

    }
}
