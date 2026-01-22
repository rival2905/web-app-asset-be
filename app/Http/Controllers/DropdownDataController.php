<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterLokasiKerja;
use App\Models\User;

class DropdownDataController extends Controller
{
    //
    public function getLokasiByUPTD(Request $request)
    {
        $idUPTD = $request->id;
        $lokasi = MasterLokasiKerja::where('uptd_id', $idUPTD)->get();
        return response()->json($lokasi);
    }
    public function getLokasiByBidang(Request $request)
    {
        $idUPTD = $request->id;
        $lokasi = MasterLokasiKerja::latest();
        if ($idUPTD<7) {
            $lokasi = $lokasi->where('uptd_id', $idUPTD); 
        }
        $lokasi = $lokasi->get();
        return response()->json($lokasi);
    }
    public function getAtasanByUnit(Request $request)
    {
        $idUPTD = $request->id;
        $pengamat = User::where('unit_id', $idUPTD)->where('role','pengamat')->get();
        return response()->json($pengamat);
    }
    public function getKSPPJByUPTD(Request $request)
    {
        $idUPTD = $request->id;
        $ksppj = User::where('uptd_id', $idUPTD)->where('role','ksppj')->get();
        return response()->json($ksppj);
    }
    public function getPengamatByUPTD(Request $request)
    {
        $idUPTD = $request->id;
        $pengamat = User::where('uptd_id', $idUPTD)->where('role','pengamat')->get();
        return response()->json($pengamat);
    }
    public function getMandorByUPTD(Request $request)
    {
        $idUPTD = $request->id;
        $mandor = User::where('uptd_id', $idUPTD)->where('role','mandor')->get();
        return response()->json($mandor);
    }

    public function getPengamatByKSPPJ(Request $request)
    {
        $idKSPPJ = $request->id;
        $pengamat = User::where('ksppj_id', $idKSPPJ)->where('role','pengamat')->get();
        return response()->json($pengamat);
    }

    public function getMandorByKSPPJ(Request $request)
    {
        $idKSPPJ = $request->id;
        $mandor = User::where('ksppj_id', $idKSPPJ)->where('role','mandor')->get();
        return response()->json($mandor);
    }

    public function getMandorByPengamat(Request $request)
    {
        $idPengamat = $request->id;
        $pengamat = User::where('pengamat_id', $idPengamat)->where('role','mandor')->get();
        return response()->json($pengamat);
    }
}
