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

        //dd($total_harga);

        //penjualan non aop

        $part_non = PartAOPMaster::where('id_part', $aop)->pluck('part_no');

        //dd($part_non);

        $getPenjualanNon = TransaksiInvoiceDetails::where('area_inv', $kode->kode_area)
            ->whereBetween('crea_date', [$tanggal_awal, $tanggal_akhir])
            ->whereIn('part_no', $part_non)
            ->get();
        // dd($getPenjualanNon);

        //PENJUALAN DBP_JUAL_NON
        $penjualan_non = 0;

        foreach($getPenjualanNon as $p) {
            $penjualan = $p->qty * $p->dbp_jual->dbp;
            $penjualan_non += $penjualan;

        }

        dd($penjualan_non);

        return redirect()->route('pembelian-aop.index')->with('success', 'Data persediaan berhasil ditambahkan!');
    }

}
