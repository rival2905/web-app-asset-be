<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use App\Models\MasterUnit;

use App\Models\Absensi;
use App\Models\User;
use App\Models\TempPeriod;
use App\Models\AnulirAbsensi;

class RekapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if(Auth::user()->uptd_id){
            $uptds = array(Auth::user()->uptd_id);
        }else{
            $uptds = array(1, 2, 3, 4, 5, 6);
        }
        return view('admin.rekap.daily',compact('uptds'));

    }

    public function daily(Request $request)
    {
        //
        $filter['tanggal_awal']= Carbon::now()->subDays(1)->format('Y-m-d');
        $filter['tanggal_akhir']= Carbon::now()->format('Y-m-d');
        // dd($filter);
        $filter['ksppj_id']= null;

        $user_check = User::whereIn('role',['pekerja','mandor'])->whereNull('deleted_at');
        $user_absences = User::whereIn('role',['pekerja','mandor'])->orderBy('name');


        $presences = Absensi::whereNotNull('jam_masuk');
        $absences = Absensi::whereNull('jam_masuk');

        $is_ksppj = Auth::user()->role == 'ksppj';

        $is_pengamat = Auth::user()->role == 'pengamat';
        $is_mandor = Auth::user()->role == 'mandor';
        $is_role = false;

        if ($is_pengamat || $is_mandor) {
            if($is_mandor){
                $user_check = $user_check->where('mandor_id', Auth::user()->id)->pluck('id')->toArray();
                $is_role = $is_mandor;
                $user_absences = $user_absences->where('mandor_id', Auth::user()->id);

            }else{
                $user_check = $user_check->where('pengamat_id', Auth::user()->id)->pluck('id')->toArray();
                $is_role = $is_pengamat;
                $user_absences = $user_absences->where('pengamat_id', Auth::user()->id);
            }
            $filter['unit_id'] = Auth::user()->master_unit_id;

            if($request->tanggal_akhir){
                $filter['tanggal_akhir'] = $request->tanggal_akhir;
            }

            $presences = $presences->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$user_check)->with('user')->get();
            $absences = $absences->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$user_check)->get();
            
            $temp_user = $presences->select('user_id')->pluck('user_id')->toArray();
            $user_absences = $user_absences->where('uptd_id', $filter['uptd_id'])->whereIn('id',$user_check)->whereNotIn('id',$temp_user);


            $user_absences = $user_absences->where(function ($query) use($filter) {
                $query->whereDate('deleted_at','>',$filter['tanggal_akhir'])
                ->orWhereNull('deleted_at');
            });

            $user_absences = $user_absences->get();

            
            $total_absen = $presences->count();
            $total_tepat_waktu = $presences->where('keterangan', '!=', 'Terlambat')->count();
            $total_terlambat = $presences->where('keterangan', 'Terlambat')->count();
            $total_absen_pulang = $presences->whereNotNull('jam_keluar')->count();

            $total_tidak_absen = count($user_check) - $total_absen;
            $total_izin_sakit = $absences->where('keterangan', 'like', 'Izin - Izin Sakit')->count();
            $total_izin_lainnya = $absences->where('keterangan', 'like', 'Izin - Izin Lainnya')->count();
            $total_tanpa_keterangan = count($user_check) - $total_absen - $total_izin_sakit - $total_izin_lainnya;

            return view('admin.rekap.daily',
                compact(
                    'presences',
                    'absences',
                    'filter',
                    'user_check',
                    'is_pengamat',
                    'is_mandor',
                    'is_role',
                    'total_absen',
                    'total_tepat_waktu',
                    'total_terlambat',
                    'total_absen_pulang',
                    'total_tidak_absen',
                    'total_izin_sakit',
                    'total_izin_lainnya',
                    'total_tanpa_keterangan',
                    'user_absences',
                    'units'
                )
            );
        }

        $ksppjs = User::where('role','ksppj');
        $units = MasterUnit::orderByRaw('RAND()');

        if(Auth::user()->master_user_id){
            $uptds = array(Auth::user()->master_unit_id);
            $units = $units->where('id', Auth::user()->master_unit_id);

        }else{
            $uptds = array(1, 2, 3, 4, 5, 6);  
        }
        $units = $units->get();

        if($request->unit_id){ 
            $filter['unit_id'] = $request->unit_id;
        }else{
            $filter['unit_id'] = $units[0]->id;
            $filter['unit_id'] = 8;

        }
        if($is_ksppj){
            $ksppjs = $ksppjs->where('id', Auth::user()->id);
        }
        $ksppjs = $ksppjs->where('master_unit_id', $filter['unit_id'])->get();
        // // dd($ksppjs);
        // if($request->ksppj_id){
        //     $filter['ksppj_id'] = $request->ksppj_id;
        // }else{
        //     $temp_ksppjs = $ksppjs->toArray();
        //     shuffle($temp_ksppjs);
        //     $filter['ksppj_id'] = $temp_ksppjs[0]['id'];
        // }
        // if($filter['ksppj_id'] != null && $filter['ksppj_id'] != "Choose..."){
        //     $user_check = $user_check->where('ksppj_id', $filter['ksppj_id']);

        // }

        $user_check = $user_check->where('master_unit_id', $filter['unit_id'])->pluck('id')->toArray();

        // dd(count($user_check));
        if($request->tanggal_akhir){
            $filter['tanggal_akhir'] = $request->tanggal_akhir;
        }

        $presences = $presences->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$user_check)->with('user')->get();
        $absences = $absences->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$user_check)->get();

        $temp_user = $presences->select('user_id')->pluck('user_id')->toArray();
        
        $user_absences = $user_absences->where('master_unit_id', $filter['unit_id'])->whereIn('id',$user_check)->whereNotIn('id',$temp_user);

        $user_absences = $user_absences->where(function ($query) use($filter) {
            $query->whereDate('deleted_at','>',$filter['tanggal_akhir'])
            ->orWhereNull('deleted_at');
        });

        $user_absences = $user_absences->get();

        // dd($user_check);

        $total_absen = $presences->count();
        $total_tepat_waktu = $presences->where('keterangan', '!=', 'Terlambat')->count();
        $total_terlambat = $presences->where('keterangan', 'Terlambat')->count();
        $total_absen_pulang = $presences->whereNotNull('jam_keluar')->count();

        $total_tidak_absen = count($user_check) - $total_absen;
        $total_izin_sakit = $absences->where('keterangan', 'like', 'Izin - Izin Sakit')->count();
        $total_izin_lainnya = $absences->where('keterangan', 'like', 'Izin - Izin Lainnya')->count();
        $total_tanpa_keterangan = count($user_check) - $total_absen - $total_izin_sakit - $total_izin_lainnya;

        return view('admin.rekap.daily',
            compact(
                'uptds',
                'presences',
                'absences',
                'filter',
                'user_check',
                'ksppjs',
                'is_pengamat',
                'is_mandor',
                'is_role',
                'total_absen',
                'total_tepat_waktu',
                'total_terlambat',
                'total_absen_pulang',
                'total_tidak_absen',
                'total_izin_sakit',
                'total_izin_lainnya',
                'total_tanpa_keterangan',
                'user_absences',
                'units'
            )
        );
    }

    public function daily_absence(Request $request)
    {
        //
        $filter['tanggal_awal']= Carbon::now()->subDays(1)->format('Y-m-d');
        $filter['tanggal_akhir']= Carbon::now()->format('Y-m-d');
        // dd($filter);
        $filter['ksppj_id']= null;

        $user_absences = User::whereIn('role',['pekerja','mandor']);

        $user_check = User::whereIn('role',['pekerja','mandor'])->whereNull('deleted_at');
        $presences = Absensi::whereNotNull('jam_masuk');
        $absences = Absensi::whereNull('jam_masuk');

        $is_ksppj = Auth::user()->role == 'ksppj';

        $is_pengamat = Auth::user()->role == 'pengamat';
        $is_mandor = Auth::user()->role == 'mandor';
        $is_role = false;

        $ksppjs = User::where('role','ksppj');

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
        if($is_ksppj){
            $ksppjs = $ksppjs->where('id', Auth::user()->id);
        }
        $ksppjs = $ksppjs->where('uptd_id', $filter['uptd_id'])->get();
        // dd($ksppjs);
        if($request->ksppj_id){
            $filter['ksppj_id'] = $request->ksppj_id;
        }else{
            $temp_ksppjs = $ksppjs->toArray();
            shuffle($temp_ksppjs);
            $filter['ksppj_id'] = $temp_ksppjs[0]['id'];
        }

        

        if ($is_pengamat || $is_mandor) {
            if($is_mandor){
                $user_check = $user_check->where('mandor_id', Auth::user()->id);
                $user_absences = $user_absences->where('mandor_id', Auth::user()->id);

                $is_role = $is_mandor;
            }else{
                $user_check = $user_check->where('pengamat_id', Auth::user()->id);
                $user_absences = $user_absences->where('pengamat_id', Auth::user()->id);

                $is_role = $is_pengamat;

            }
            $filter['uptd_id'] = Auth::user()->uptd_id;
            $filter['ksppj_id'] = Auth::user()->ksppj_id;
        }


        if($filter['ksppj_id'] != null && $filter['ksppj_id'] != "Choose..."){
            $user_check = $user_check->where('ksppj_id', $filter['ksppj_id']);
            $user_absences = $user_absences->where('ksppj_id', $filter['ksppj_id']);

        }
        $user_check = $user_check->where('uptd_id', $filter['uptd_id'])->pluck('id')->toArray();

        // dd(count($user_check));
        if($request->tanggal_akhir){
            $filter['tanggal_akhir'] = $request->tanggal_akhir;
        }
        
        $presences = $presences->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$user_check)->with('user')->get();
        $absences = $absences->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$user_check)->get();

        $temp_user = $presences->select('user_id')->pluck('user_id')->toArray();
        $user_absences = $user_absences->where('uptd_id', $filter['uptd_id'])->whereNotIn('id',$temp_user);

        $user_absences = $user_absences->where(function ($query) use($filter) {
            $query->whereDate('deleted_at','>',$filter['tanggal_akhir'])
            ->orWhereNull('deleted_at');
        });

        $user_absences = $user_absences->get();

        $total_absen = $presences->count();
        $total_tepat_waktu = $presences->where('keterangan', '!=', 'Terlambat')->count();
        $total_terlambat = $presences->where('keterangan', 'Terlambat')->count();
        $total_absen_pulang = $presences->whereNotNull('jam_keluar')->count();

        $total_tidak_absen = count($user_check) - $total_absen;
        $total_izin_sakit = $absences->where('keterangan', 'like', 'Izin - Izin Sakit')->count();
        $total_izin_lainnya = $absences->where('keterangan', 'like', 'Izin - Izin Lainnya')->count();
        $total_tanpa_keterangan = count($user_check) - $total_absen - $total_izin_sakit - $total_izin_lainnya;
        // dd(Auth::user()->ksppj_id);
        // return view('admin.rekap.daily-absence',compact('uptds','presences','absences','filter','user_check','user_absences','ksppjs'));
        return view('admin.rekap.daily-absence',
            compact(
                'user_absences',
                'uptds',
                'presences',
                'absences',
                'filter',
                'user_check',
                'ksppjs',
                'is_pengamat',
                'is_mandor',
                'is_role',
                'total_absen',
                'total_tepat_waktu',
                'total_terlambat',
                'total_absen_pulang',
                'total_tidak_absen',
                'total_izin_sakit',
                'total_izin_lainnya',
                'total_tanpa_keterangan',
            )
        );

    }

    public function export_daily(Request $request, $description)
    {
        //
        $key = explode("&-",$description);
        try {
            $filter['uptd_id'] = Crypt::decryptString($key[1]);
        } catch (DecryptException $e) {
            return back()->with('error', 'Terjadi kesalahan ketika mengakses halaman!!')->withInput();
        }

        $is_ksppj = Auth::user()->role == 'ksppj';

        $is_pengamat = Auth::user()->role == 'pengamat';
        $is_mandor = Auth::user()->role == 'mandor';
        $is_role = false;

        $filter['ksppj_id'] = null;
        $ksppj = null;
        if($request->tanggal_akhir){
            $filter['tanggal_akhir'] = $request->tanggal_akhir;
        }
        if($request->uptd_id){
            $filter['uptd_id'] = $request->uptd_id;
        }
        $user_check = User::whereIn('role',['pekerja','mandor'])->where('uptd_id', $filter['uptd_id']);

        if($request->ksppj_id){
            $filter['ksppj_id'] = $request->ksppj_id;
        }
        if($filter['ksppj_id'] != null && $filter['ksppj_id'] != "Choose..."){
            $user_check = $user_check->where('ksppj_id', $filter['ksppj_id']);
            $ksppj = User::where('id',$filter['ksppj_id'])->first();
        }
        $user_check = $user_check->pluck('id')->toArray();

        if($key[0] =='presence'){
            $data = Absensi::whereNotNull('jam_masuk')->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$user_check)->get();
        }else{
            $user_presences = Absensi::whereNotNull('jam_masuk')->wheredate('tanggal',$filter['tanggal_akhir'])->whereIn('user_id',$user_check)->select('user_id')->pluck('user_id')->toArray();
            $data = User::whereIn('role',['pekerja','mandor'])->whereNotIn('id',$user_presences);

            if ($is_pengamat || $is_mandor) {
                if($is_mandor){
                    $data = $data->where('mandor_id', Auth::user()->id);
                }else{
                    $data = $data->where('pengamat_id', Auth::user()->id);
                }
                $filter['uptd_id'] = Auth::user()->uptd_id;
                $filter['ksppj_id'] = Auth::user()->ksppj_id;
            }

            $data = $data->where('uptd_id', $filter['uptd_id']);
            if($filter['ksppj_id']){
                $data = $data->where('ksppj_id', $filter['ksppj_id']);
            }
            $data = $data->where(function ($query) use($filter) {
                $query->whereDate('deleted_at','>',$filter['tanggal_akhir'])
                ->orWhereNull('deleted_at');
            });

            $data = $data->get();
        }
        // dd($data);
        return view('admin.rekap.daily-export',compact('data','filter','key','ksppj'));


    }


    public function user(Request $request, $id)
    {
        //
        try {
            $id = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            return back()->with('error', 'Terjadi kesalahan ketika mengakses halaman!!')->withInput();
        }
        $filter['year']= Carbon::now()->format('Y');
        $filter['month']= Carbon::now()->format('m');
        $filter['now']= Carbon::now()->format('Y-m-d');

        $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        
        if($request->month){
            $temp_month= explode("-",$request->month);
            $filter['year']= $temp_month[0];
            $filter['month']= $temp_month[1];
            $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        }

        $user = User::find($id);

        // Menentukan tanggal awal dan akhir dari bulan yang dipilih
        $startDate = Carbon::create($filter['year'], $filter['month'], 1);
        $endDate = $startDate->copy()->endOfMonth();
        $totalDays = $endDate->day;

        // Menghitung titik pembagian untuk dua periode
        $midPoint = ceil($totalDays / 2);

        // Mendefinisikan array periode
        $data_temp['periode1'] = ['year' => $filter['year'], 'dates' => [], 'full_dates' => []];
        $data_temp['periode2'] = ['year' => $filter['year'], 'dates' => [], 'full_dates' => []];

        // Memisahkan tanggal menjadi dua periode
        foreach (range(1, $totalDays) as $day) {
            $date = Carbon::create($filter['year'], $filter['month'], $day);
            if ($day <= $midPoint) {
                $data_temp['periode1']['dates'][] = $date->day;
                $data_temp['periode1']['full_dates'][] = $date->format('Y-m-d');
            } else {
                $data_temp['periode2']['dates'][] = $date->day;
                $data_temp['periode2']['full_dates'][] = $date->format('Y-m-d');
            }
        }
        $presences = $user->absensi()->whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->get();
        // dd($presences);
        // dd($data_temp);
        // dd($user->absensi()->whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->get());
        return view('admin.rekap.user',compact('user','filter','data_temp','presences'));
    }
    public function user_anulir($id)
    {
        //
        try {
            $id = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            return back()->with('error', 'Terjadi kesalahan ketika mengakses halaman!!')->withInput();
        }

        $user = User::find($id);

        $presences = $user->data_anulir()->get();

        return view('admin.rekap.user-anulir',compact('user','presences'));
    }
    function generate_periode($data,$year,$month)
    {

        // Menentukan tanggal awal dan akhir dari bulan yang dipilih
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $totalDays = $endDate->day;

        // Menghitung titik pembagian untuk dua periode
        $midPoint = ceil($totalDays / 2);

        // Mendefinisikan array periode
        $periode1 = ['year' => $year, 'dates' => [], 'full_dates' => []];
        $periode2 = ['year' => $year, 'dates' => [], 'full_dates' => []];
        $full_periode = ['year' => $year, 'dates' => [], 'full_dates' => []];

        // Memisahkan tanggal menjadi dua periode dan mengabaikan hari Minggu
        foreach (range(1, $totalDays) as $day) {
            $date = Carbon::create($year, $month, $day);
            // if ($date->isSunday()) {
            //     continue;
            // }

            if ($day <= $midPoint) {
                $periode1['dates'][] = $date->day;
                $periode1['full_dates'][] = $date->format('Y-m-d');
            } else {
                $periode2['dates'][] = $date->day;
                $periode2['full_dates'][] = $date->format('Y-m-d');
            }
            $full_periode['dates'][] = $date->day;
            $full_periode['full_dates'][] = $date->format('Y-m-d');
        }
        $temporari = [
            'tanggal'      => $data->tanggal,
            'first_periode' =>json_encode($periode1),
            'second_periode'=>json_encode($periode2),
            'full_periode'=>json_encode($full_periode),
        ];
        $temporari['start_first_periode'] = $periode1['full_dates'][0];
        $temporari['end_first_periode'] = $periode1['full_dates'][count($periode1['full_dates']) - 1 ];
        $temporari['start_second_periode'] = $periode2['full_dates'][0];
        $temporari['end_second_periode'] = $periode2['full_dates'][count($periode2['full_dates']) - 1 ];
        $temporari['start_full_periode'] = $full_periode['full_dates'][0];
        $temporari['end_full_periode'] = $full_periode['full_dates'][count($full_periode['full_dates']) - 1 ];
        $item = TempPeriod::insert($temporari);
        return $item;

    }

    public function monthly(Request $request)
    {
        //
        $filter['year']= Carbon::now()->format('Y');
        $filter['month']= Carbon::now()->format('m');
        $filter['now']= Carbon::now()->format('Y-m-d');

        $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        
        if($request->month){
            $temp_month= explode("-",$request->month);
            $filter['year']= $temp_month[0];
            $filter['month']= $temp_month[1];
            $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        }
        $data_temp['periode'] = null;
        $temp_date = Absensi::whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->first();
        $temp_periode = TempPeriod::whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->first();

        if($temp_date){
            if(!$temp_periode){
                $this->generate_periode($temp_date,$filter['year'],$filter['month']);
                $temp_periode = TempPeriod::whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->first();
            }
            $data_temp['periode'] = json_decode($temp_periode->full_periode);
            $ket_periode = 'full';

        }else{
            return back()->with('error', 'Data tidak tersedia!!')->withInput();

        }

        $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        
        if($request->month){
            $temp_month= explode("-",$request->month);
            $filter['year']= $temp_month[0];
            $filter['month']= $temp_month[1];
            $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        }

        $filter['ksppj_id']= null;
        $filter['pengamat_id']= null;
        $filter['mandor_id']= null;

        $ksppjs = User::where('role','ksppj');
        $pengamats = User::where('role','pengamat');
        $mandors = User::where('role','mandor');

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
        $ksppjs = $ksppjs->where('uptd_id', $filter['uptd_id']);
        $pengamats = $pengamats->where('uptd_id', $filter['uptd_id']);
        $mandors = $mandors->where('uptd_id', $filter['uptd_id']);
        $user_check = User::whereIn('role',['pekerja','mandor'])->where('uptd_id', $filter['uptd_id']);
        // $dor = 23;
        // dd(
        //     User::join('absensis','users.id', '=','absensis.user_id')->select('users.name')
        //     ->where(function ($query)use ($dor) {
        //         $query->where('users.mandor_id',$dor)->orWhere('users.id',$dor);
        //     })
        //     ->whereIn('users.role',['pekerja','mandor'])
        //     ->whereYear('absensis.tanggal',$filter['year'])->whereMonth('absensis.tanggal',$filter['month'])
        //     ->orderBy('users.name','Asc')->orderBy('absensis.tanggal','Asc')->get()
        // );
        $data_absen =  User::join('absensis','users.id', '=','absensis.user_id')
        ->select('users.id','users.name','users.jabatan','absensis.tanggal','absensis.jam_masuk','absensis.jam_keluar')
        ->whereIn('users.role',['pekerja','mandor'])->where('users.uptd_id', $filter['uptd_id']);

        $is_subkoor = Auth::user()->role == 'subkoor';
        $is_ksppj = Auth::user()->role == 'ksppj';
        $is_pengamat = Auth::user()->role == 'pengamat';
        $is_mandor = Auth::user()->role == 'mandor';

        if($is_ksppj){
            $filter['ksppj_id'] = Auth::user()->id;
            $ksppjs = $ksppjs->where('id', Auth::user()->id);
        }
        if($is_pengamat){
            $filter['ksppj_id'] = Auth::user()->ksppj_id;
            $filter['pengamat_id'] = Auth::user()->id;
            $ksppjs = $ksppjs->where('id', Auth::user()->ksppj_id);
            $pengamats = $pengamats->where('id', Auth::user()->id);
        }
        if($is_mandor){
            $filter['ksppj_id'] = Auth::user()->ksppj_id;
            $filter['pengamat_id'] = Auth::user()->pengamat_id;
            $filter['mandor_id'] = Auth::user()->id;
            $ksppjs = $ksppjs->where('id', Auth::user()->ksppj_id);
            $pengamats = $pengamats->where('id', Auth::user()->pengamat_id);
            $mandors = $mandors->where('id', Auth::user()->id);
        }

        if($request->ksppj_id){
            $filter['ksppj_id'] = $request->ksppj_id;
        }
        if($request->pengamat_id){
            $filter['pengamat_id'] = $request->pengamat_id;
        }
        if($request->mandor_id){
            $filter['mandor_id'] = $request->mandor_id;
        }
        // dd($filter);
        if($filter['ksppj_id'] != null ){
            if($filter['ksppj_id'] == "Choose..."){
                return back()->with('error', 'KSPPJ Wajib di pilih')->withInput();
            }else{
                $pengamats = $pengamats->where('ksppj_id', $filter['ksppj_id']);
                $mandors = $mandors->where('ksppj_id', $filter['ksppj_id']);
            }
        }
        if($filter['pengamat_id'] != null ){
            if($filter['pengamat_id'] == "Choose..."){
                $filter['pengamat_id'] = null;
                return back()->with('error', 'Pengamat Wajib di pilih')->withInput();
            }else{
                $mandors = $mandors->where('pengamat_id', $filter['pengamat_id']);

            }
        }
        if($filter['mandor_id'] != null ){
            if($filter['mandor_id'] == "Choose..."){
                $filter['mandor_id'] = null;
            }
        }

        $ksppjs = $ksppjs->get();
        $pengamats = $pengamats->get();
        $mandors = $mandors->get();

        if($filter['ksppj_id']){
            if($filter['pengamat_id'] || $filter['mandor_id']){
                if($filter['mandor_id']){
                    $user_check = $user_check->where('mandor_id',$filter['mandor_id'])->orWhere('id',$filter['mandor_id']);
                    $dor=$filter['mandor_id'];
                    $data_absen = $data_absen->where(function ($query) use ($dor) {
                        $query->where('users.mandor_id',$dor)->orWhere('users.id',$dor);
                    });

                } else {
                    $user_check = $user_check->where('pengamat_id',$filter['pengamat_id']);
                    $data_absen = $data_absen->where('users.pengamat_id',$filter['pengamat_id']);

                }
            }
            $user_check = $user_check->where('ksppj_id',$filter['ksppj_id']);
            $data_absen = $data_absen->where('users.ksppj_id',$filter['ksppj_id']);

        }
        // $user_check = $user_check->whereYear('deleted_at','<',$filter['year'])->whereMonth('deleted_at','<',$filter['month'])->orderBy('role','desc')->get();
        // $query->where(function ($query2) use($filter) { $query2->whereYear('deleted_at','<',$filter['year'])->whereMonth('deleted_at','<',$filter['month']);})
        $user_check = $user_check->where(function ($query) use($filter) {
            $query->whereDate('deleted_at','>',$filter['tanggalAkhirBulan'])
            ->orWhereNull('deleted_at');
        })
        ->orderBy('role','desc')->get();

        $data_absen = $data_absen->whereYear('absensis.tanggal',$filter['year'])->whereMonth('absensis.tanggal',$filter['month'])->orderBy('users.name','Asc')->orderBy('absensis.tanggal','Asc')->get()->toArray();

        // dd(count($data_temp['periode']->dates));
        // dd($data_absen);

        
        return view('admin.rekap.monthly',compact('uptds','ksppjs','pengamats','mandors','filter','user_check','data_temp','data_absen','ket_periode','temp_periode','is_ksppj','is_pengamat','is_mandor'));

    }
    public function export_monthly(Request $request)
    {
        //
        $filter['year']= Carbon::now()->format('Y');
        $filter['month']= Carbon::now()->format('m');
        $filter['now']= Carbon::now()->format('Y-m-d');

        $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        
        if($request->month){
            $temp_month= explode("-",$request->month);
            $filter['year']= $temp_month[0];
            $filter['month']= $temp_month[1];
            $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        }

        $data_temp['periode'] = null;
        $temp_date = Absensi::whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->first();
        $temp_periode = TempPeriod::whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->first();

        if($temp_date){
            $ket_periode = 'full';

            if(!$temp_periode){
                $this->generate_periode($temp_date,$filter['year'],$filter['month']);
                $temp_periode = TempPeriod::whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->first();
            }
            $data_temp['periode'] = json_decode($temp_periode->full_periode);

        }else{
            return back()->with('error', 'Data tidak tersedia!!')->withInput();

        }

        $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        
        if($request->month){
            $temp_month= explode("-",$request->month);
            $filter['year']= $temp_month[0];
            $filter['month']= $temp_month[1];
            $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        }

        $filter['ksppj_id']= null;
        $filter['pengamat_id']= null;
        $filter['mandor_id']= null;

        $ksppj = User::where('role','ksppj');
        $pengamat = User::where('role','pengamat');
        $mandor = User::where('role','mandor');

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
        $ksppj = $ksppj->where('uptd_id', $filter['uptd_id']);
        $pengamat = $pengamat->where('uptd_id', $filter['uptd_id']);
        $mandor = $mandor->where('uptd_id', $filter['uptd_id']);
        $user_check = User::whereIn('role',['pekerja','mandor'])->where('uptd_id', $filter['uptd_id']);

        $data_absen =  User::join('absensis','users.id', '=','absensis.user_id')->select('users.id','users.name','users.jabatan','absensis.tanggal','absensis.jam_masuk','absensis.jam_keluar')->whereIn('users.role',['pekerja','mandor'])->where('users.uptd_id', $filter['uptd_id']);

        if($request->ksppj_id){
            $filter['ksppj_id'] = $request->ksppj_id;
        }
        if($request->pengamat_id){
            $filter['pengamat_id'] = $request->pengamat_id;
        }
        if($request->mandor_id){
            $filter['mandor_id'] = $request->mandor_id;
        }

        if($filter['ksppj_id'] != null ){
            if($filter['ksppj_id'] == "Choose..."){
                return back()->with('error', 'KSPPJ Wajib di pilih')->withInput();
            }else{
                $ksppj = $ksppj->where('id',$filter['ksppj_id']);
                $pengamat = $pengamat->where('ksppj_id', $filter['ksppj_id']);
                $mandor = $mandor->where('ksppj_id', $filter['ksppj_id']);
            }
        }
        if($filter['pengamat_id'] != null ){
            if($filter['pengamat_id'] == "Choose..."){
                $filter['pengamat_id'] = null;
                return back()->with('error', 'Pengamat Wajib di pilih')->withInput();
            }else{
                $pengamat = $pengamat->where('id',$filter['pengamat_id']);
                $mandor = $mandor->where('pengamat_id', $filter['pengamat_id']);

            }
        }
        if($filter['mandor_id'] != null ){
            if($filter['mandor_id'] == "Choose..."){
                $filter['mandor_id'] = null;
            }else{
                $mandor = $mandor->where('id',$filter['mandor_id']);
            }
        }

        $ksppj = $ksppj->first();
        $pengamat = $pengamat->first();
        $mandor = $mandor->first();

        if($filter['ksppj_id']){
            if($filter['pengamat_id'] || $filter['mandor_id']){
                if($filter['mandor_id']){
                    $user_check = $user_check->where('mandor_id',$filter['mandor_id'])->orWhere('id',$filter['mandor_id']);
                    $dor=$filter['mandor_id'];
                    $data_absen = $data_absen->where(function ($query) use ($dor) {
                        $query->where('users.mandor_id',$dor)->orWhere('users.id',$dor);
                    });

                } else {
                    $user_check = $user_check->where('pengamat_id',$filter['pengamat_id']);
                    $data_absen = $data_absen->where('users.pengamat_id',$filter['pengamat_id']);

                }
            }
            $user_check = $user_check->where('ksppj_id',$filter['ksppj_id']);
            $data_absen = $data_absen->where('users.ksppj_id',$filter['ksppj_id']);

        }
        // $user_check = $user_check->orderBy('role','desc')->get();
        $user_check = $user_check->where(function ($query) use($filter) {
            $query->whereDate('deleted_at','>',$filter['tanggalAkhirBulan'])
            ->orWhereNull('deleted_at');
        })
        ->orderBy('role','desc')->get();
        $data_absen = $data_absen->whereYear('absensis.tanggal',$filter['year'])->whereMonth('absensis.tanggal',$filter['month'])->orderBy('users.name','Asc')->orderBy('absensis.tanggal','Asc')->get()->toArray();


        $filter['bulan']= Carbon::create($filter['year'], $filter['month'])->locale('id_ID')->isoFormat('MMMM Y');

        // dd($filter);
        return view('admin.rekap.monthly-export',compact('uptds','ksppj','pengamat','mandor','filter','user_check','data_temp','data_absen','ket_periode','temp_periode'));

    }


    public function periode($category, Request $request)
    {
        //
        $filter['year']= Carbon::now()->format('Y');
        $filter['month']= Carbon::now()->format('m');
        $filter['now']= Carbon::now()->format('Y-m-d');

        $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        
        if($request->month){
            $temp_month= explode("-",$request->month);
            $filter['year']= $temp_month[0];
            $filter['month']= $temp_month[1];
            $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        }
        $data_temp['periode'] = null;
        $temp_date = Absensi::whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->first();
        $temp_periode = TempPeriod::whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->first();

        if($temp_date){
            if(!$temp_periode){
                $this->generate_periode($temp_date,$filter['year'],$filter['month']);
                $temp_periode = TempPeriod::whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->first();
            }

            $ket_periode = 'full';
            if($category == "first_periode"){
                $data_temp['periode'] = json_decode($temp_periode->first_periode);
                $ket_periode = 'first';

            }else if($category="second_periode"){
                $data_temp['periode'] = json_decode($temp_periode->second_periode);
                $ket_periode = 'second';

            }

        }else{
            return back()->with('error', 'Data tidak tersedia!!')->withInput();

        }

        $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        
        if($request->month){
            $temp_month= explode("-",$request->month);
            $filter['year']= $temp_month[0];
            $filter['month']= $temp_month[1];
            $filter['tanggalAkhirBulan'] = Carbon::create($filter['year'], $filter['month'], 1)->endOfMonth();
        }

        $filter['ksppj_id']= null;
        $filter['pengamat_id']= null;
        $filter['mandor_id']= null;

        $ksppj = User::where('role','ksppj');
        $pengamat = User::where('role','pengamat');
        $mandor = User::where('role','mandor');

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
        $ksppj = $ksppj->where('uptd_id', $filter['uptd_id']);
        $pengamat = $pengamat->where('uptd_id', $filter['uptd_id']);
        $mandor = $mandor->where('uptd_id', $filter['uptd_id']);
        $user_check = User::whereIn('role',['pekerja','mandor'])->where('uptd_id', $filter['uptd_id']);

        $data_absen =  User::join('absensis','users.id', '=','absensis.user_id')->select('users.id','users.name','users.jabatan','absensis.tanggal','absensis.jam_masuk','absensis.jam_keluar')->whereIn('users.role',['pekerja','mandor'])->where('users.uptd_id', $filter['uptd_id']);

        if($request->ksppj_id){
            $filter['ksppj_id'] = $request->ksppj_id;
        }
        if($request->pengamat_id){
            $filter['pengamat_id'] = $request->pengamat_id;
        }
        if($request->mandor_id){
            $filter['mandor_id'] = $request->mandor_id;
        }

        if($filter['ksppj_id'] != null ){
            if($filter['ksppj_id'] == "Choose..."){
                return back()->with('error', 'KSPPJ Wajib di pilih')->withInput();
            }else{
                $ksppj = $ksppj->where('id',$filter['ksppj_id']);
                $pengamat = $pengamat->where('ksppj_id', $filter['ksppj_id']);
                $mandor = $mandor->where('ksppj_id', $filter['ksppj_id']);
            }
        }
        if($filter['pengamat_id'] != null ){
            if($filter['pengamat_id'] == "Choose..."){
                $filter['pengamat_id'] = null;
                return back()->with('error', 'Pengamat Wajib di pilih')->withInput();
            }else{
                $pengamat = $pengamat->where('id',$filter['pengamat_id']);
                $mandor = $mandor->where('pengamat_id', $filter['pengamat_id']);

            }
        }
        if($filter['mandor_id'] != null ){
            if($filter['mandor_id'] == "Choose..."){
                $filter['mandor_id'] = null;
            }else{
                $mandor = $mandor->where('id',$filter['mandor_id']);
            }
        }

        $ksppj = $ksppj->first();
        $pengamat = $pengamat->first();
        $mandor = $mandor->first();

        if($filter['ksppj_id']){
            if($filter['pengamat_id'] || $filter['mandor_id']){
                if($filter['mandor_id']){
                    $user_check = $user_check->where('mandor_id',$filter['mandor_id'])->orWhere('id',$filter['mandor_id']);
                    $dor=$filter['mandor_id'];
                    $data_absen = $data_absen->where(function ($query) use ($dor) {
                        $query->where('users.mandor_id',$dor)->orWhere('users.id',$dor);
                    });

                } else {
                    $user_check = $user_check->where('pengamat_id',$filter['pengamat_id']);
                    $data_absen = $data_absen->where('users.pengamat_id',$filter['pengamat_id']);

                }
            }
            $user_check = $user_check->where('ksppj_id',$filter['ksppj_id']);
            $data_absen = $data_absen->where('users.ksppj_id',$filter['ksppj_id']);

        }
        $user_check = $user_check->orderBy('role','desc')->get();
        $data_absen = $data_absen->whereYear('absensis.tanggal',$filter['year'])->whereMonth('absensis.tanggal',$filter['month'])->orderBy('users.name','Asc')->orderBy('absensis.tanggal','Asc')->get()->toArray();


        $filter['bulan']= Carbon::create($filter['year'], $filter['month'])->locale('id_ID')->isoFormat('MMMM Y');

        // dd($filter);
        return view('admin.rekap.monthly-export',compact('uptds','ksppj','pengamat','mandor','filter','user_check','data_temp','data_absen','ket_periode','temp_periode'));
    }

    
    public function help_presensi(Request $request){

        $this->validate($request, [
            'name'      => 'required',
            'image'     => 'required|image|mimes:png,jpg,webp,jpeg,svg|max:2048',
            'keterangan'   => '',
            'latitude'   => 'required',
            'longitude'   => 'required',
            'tanggal'      => 'required',


        ]);
        $user = User::find($request->name);
        if($user){
            $direct=[
                'uptd_id' => $user->uptd_id,
                'ksppj_id' => $user->ksppj_id,
                'tanggal_akhir' => $request->tanggal,

            ];

            $absensi = Absensi::where('user_id', $user->id)->where('tanggal', $request->tanggal)->latest()->first();
            $file = $request->file('image');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            
            if (!$absensi) {
                $temp_data = [
                    'user_id' => $user->id,
                    'tanggal' => $request->tanggal,
                    'jam_masuk' => date("H:i:s"),
                    'lokasi_masuk' => "Absen Oleh " . Auth::user()->name,
                    'latitude_masuk' => $request->latitude ?? null,
                    'longitude_masuk' => $request->longitude ?? null,
                    'foto_masuk' => $nama_file,
                    'keterangan' => "Absen Oleh " . Auth::user()->name,
                ];
                if($request->keterangan){
                    $temp_data['keterangan'] ="Absen Oleh " . Auth::user()->name . " : " . $request->keterangan;
                }
                Absensi::create($temp_data);
                Storage::putFileAs('public/foto_absensi/masuk/', $file, $nama_file);
                // return back()->with(['success' => 'Absen masuk berhasil!']);
                return redirect()->route('admin.rekap.daily', $direct)->with(['success' => 'Absen masuk berhasil!']);
    
            } else {
                return redirect()->route('admin.rekap.daily', $direct)->with('error', 'Absen GAGAL!')->withInput();
                // $file = $request->file('image');
                // $nama_file = time() . "_" . $file->getClientOriginalName();
    
                // $absensi->update([
                //     'jam_keluar' => date("H:i:s"),
                //     'lokasi_keluar' => "Absen Oleh " . Auth::user()->name,
                //     'latitude_keluar' => $request->latitude ?? null,
                //     'longitude_keluar' => $request->longitude ?? null,
                //     'foto_keluar' => $nama_file,
    
                // ]);
                // Storage::putFileAs('public/foto_absensi/keluar/', $file, $nama_file);
    
                // return back()->with(['success' => 'Absen pulang berhasil!']);
    
            }
        }else {
                return back()->with('error', 'Absen GAGAL!')->withInput();
        }
    }

    public function data_anulir($user_id,$id){
        $user = User::find($user_id);
        if($user){
            $presences = $user->absensi()->where('id',$id)->first();
            if($presences){
                $presences_arr = $presences->toArray();
                $presences_arr['absensi_id'] = $presences['id'];
                $presences_arr['deleted_by'] = Auth::user()->id;
                $presences_arr['deleted_at'] = Carbon::now()->toDateTimeString();
                array_shift($presences_arr);
                // dd($presences_arr);
                $anulir = AnulirAbsensi::create($presences_arr);
                $presences->delete();
                return back()->with(['success' => 'Data Berhasil di Anulir']);
            }else{
                return back()->with('error', 'Data tidak ditemukan')->withInput();
            }
        }else{
                return back()->with('error', 'Data tidak ditemukan')->withInput();
        }
    }
    public function restore_data_anulir($user_id,$id){
        $user = User::find($user_id);
        if($user){
            $presences = $user->data_anulir()->where('id',$id)->first();
            if($presences){
                $presences_arr = $presences->toArray();
                $absensi = $user->absensi()->where('tanggal', $presences_arr['tanggal'])->latest()->first();
                if(!$absensi){
                    $presences_arr['id'] = $presences['absensi_id'];
                    $presences_arr['deleted_by'] = Auth::user()->id;
                    $presences_arr['deleted_at'] = Carbon::now()->toDateTimeString();
                    $presences_arr = Arr::except($presences_arr, ['absensi_id', 'deleted_by', 'deleted_at']);
    
                    $restore = Absensi::create($presences_arr);
                }

                $presences->delete();
                return back()->with(['success' => 'Data Berhasil di pulihkan']);
            }else{
                return back()->with('error', 'Data tidak ditemukan')->withInput();
            }
        }else{
                return back()->with('error', 'Data tidak ditemukan')->withInput();
        }
    }
}
