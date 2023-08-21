<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Kode;
use App\Models\PartNonHeader;
use App\Models\PartNonDetails;
use App\Models\PartAOPMaster;
use App\Models\NilaiPersediaanNon;
use App\Models\TransaksiInvoiceDetails;
use App\Models\TransaksiReturHeader;

class PembelianNonController extends Controller
{

    public function index(){

        $getPersediaan = NilaiPersediaanNon::all();

        return view('non-aop.index', compact('getPersediaan'));
    }

    public function prosesPersediaan(Request $request){

        $tanggal_awal   = $request->input('tanggal_awal');
        $tanggal_akhir  = $request->input('tanggal_akhir');
        $kd             = $request->input('area_inv');
        $aop            = 2;


        //PILIH PERSEDIAAN
        $tanggal = new Carbon($tanggal_awal);
        $bulan = $tanggal->format('m');
        $tahun = $tanggal->format('Y');
        
        //PILIH PERSEDIAAN AKHIR BULAN SEBELUMNYA
        $bulan_prev = $tanggal->subMonth()->format('m');

        $kode = Kode::where('kode_area',$kd)
            ->first();


        //pembelian non aop
        $non = PartNonHeader::whereBetween('tgl_nota', [$tanggal_awal, $tanggal_akhir])->get();

        $total_harga = 0;

        foreach($non as $n){
            
            $harga = $n->total_harga;
            $total_harga += $harga;

        }

        //penjualan non aop

        $part_non = PartAOPMaster::where('id_part', $aop)->pluck('part_no');

        $getPenjualanNon = TransaksiInvoiceDetails::where('area_inv', $kode->kode_area)
            ->whereBetween('crea_date', [$tanggal_awal, $tanggal_akhir])
            ->whereIn('part_no', $part_non)
            ->get();
      

        //PENJUALAN DBP_JUAL_NON
        $penjualan_non = 0;

        foreach($getPenjualanNon as $p) {
            $penjualan = $p->qty * $p->dbp_jual->dbp;
            $penjualan_non += $penjualan;

        }

        //RETUR TOKO NON

        $getReturNon = TransaksiReturHeader::whereBetween('flag_approve1_date', [$tanggal_awal, $tanggal_akhir])
            ->where('area_retur', $kode->kode_area)
            ->where('flag_approve1', 'Y')
            ->get();

        $retur_aop = 0;

        foreach($getReturNon as $r){
            $retur = $r->details_retur->whereIn('part_no', $part_non);

            foreach($retur as $n){
                if (isset($n->nominal_total) && is_numeric($n->nominal_total))
                {
                    $total_retur = $n->nominal_total;
                    $retur_aop += $total_retur;
                }                
            }
        }

        return redirect()->route('non-aop.index')->with('success', 'Data persediaan berhasil ditambahkan!');
    }

}
