<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Kode;
use App\Models\InitMasterPart;
use App\Models\ReturAOPDetails;
use App\Models\ReturAOPHeader;
use App\Models\PartAOPMaster;
use App\Models\TransaksiInvoiceDetails;
use App\Models\InvoiceAOPDetails;
use App\Models\InvoiceAOPHeader;
use App\Models\NilaiPersediaan;
use App\Models\TransaksiReturHeader;
use App\Models\SuratTagihan;


class PembelianAOPController extends Controller
{
    
    public function index(){

        $persediaan = NilaiPersediaan::all();

        return view('pembelian-aop.index', compact('persediaan'));
    }

    public function prosesPersediaan(Request $request){

        $tanggal_awal   = $request->input('tanggal_awal');
        $tanggal_akhir  = $request->input('tanggal_akhir');
        $kd             = $request->input('area_inv');
        $aop            = 1;

        //PILIH PERSEDIAAN
        $tanggal = new Carbon($tanggal_awal);
        $bulan = $tanggal->format('m');
        $tahun = $tanggal->format('Y');
        
        //PILIH PERSEDIAAN AKHIR BULAN SEBELUMNYA
        $bulan_prev = $tanggal->subMonth()->format('m');

        $kode = Kode::where('kode_area',$kd)
            ->first();

        $stock = InitMasterPart::where('kd_gudang', $kode->kode_gudang)
            ->get();

        //Init Januari 2023
        $total_init = 0;

        foreach ($stock as $s){
            $init = $s->amt_stock_akhir;
            $total_init += $init;
        }

        //PEMBELIAN AOP (/1.11)
        $getPembelianAOP = InvoiceAOPHeader::where('kd_gudang', $kode->kode_gudang)
            ->whereBetween('billingDocument_date', [$tanggal_awal, $tanggal_akhir])
            ->get();

        $dbp_pembelian = 0;

        foreach($getPembelianAOP as $p){
            $invoice_details = $p->details;

            foreach($invoice_details as $h){
                if (
                    isset($h->qty, $h->dbp_jual->dbp) &&
                    is_numeric($h->qty) && is_numeric($h->dbp_jual->dbp)
                ) {
                    $dbp = $h->qty * $h->dbp_jual->dbp;
                    $dbp_pembelian += $dbp;
                }
            }

        }

        //RETUR AOP
        $getReturAOP = ReturAOPHeader::whereBetween('crea_date', [$tanggal_awal, $tanggal_akhir])
            ->where('kd_gudang_aop', $kode->kode_kcp)
            ->where('approve_aop', 'Y')
            ->get();

        $retur_aop = $getReturAOP->sum('amount_total');

        //penjualan part_aop
        $part_aop = PartAOPMaster::where('id_part', $aop)->pluck('part_no');

        $getPenjualan = TransaksiInvoiceDetails::where('area_inv', $kode->kode_area)
            ->whereBetween('crea_date', [$tanggal_awal, $tanggal_akhir])
            ->whereIn('part_no', $part_aop)
            ->get();

        //PENJUALAN DBP_JUAL
        $penjualan = 0;

        foreach($getPenjualan as $p) {
            $penjualan_dbp = $p->qty * $p->dbp_jual->dbp;
            $penjualan += $penjualan_dbp;

        }

        //RETUR PENJUALAN
        $getReturPenjualan = TransaksiReturHeader::where('area_retur', $kode->kode_area)
            ->whereBetween('flag_approve1_date', [$tanggal_awal, $tanggal_akhir])
            ->where('flag_approve1', 'Y')
            ->get();

        $sum_total_retur = 0;

        foreach($getReturPenjualan as $r){
            $retur = $r->details_retur->whereIn('part_no', $part_aop);

            foreach($retur as $n){
                if (isset($n->nominal_total) && is_numeric($n->nominal_total))
                {
                    $total_retur = $n->nominal_total;
                    $sum_total_retur += $total_retur;
                }                
            } 
        }

        //INSERT KE TABEL NILAI PERSEDIAAN
        $checkData = NilaiPersediaan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('area_inv', $kd)
            ->first();

        $getPersediaanAkhirPrev = NilaiPersediaan::where('bulan', $bulan_prev)->where('area_inv', $kd)->pluck('persediaan_akhir')->first();       

        // if ($checkData === null) {

        //     //init Januari 2023
        //     $data['tanggal_awal']           = $tanggal_awal;
        //     $data['tanggal_akhir']          = $tanggal_akhir;
        //     $data['bulan']                  = $bulan;
        //     $data['tahun']                  = $tahun;
        //     $data['area_inv']               = $kd;
        //     $data['persediaan_awal']        = $total_init;
        //     $data['pembelian']              = $dbp_pembelian;
        //     $data['retur_aop']              = $retur_aop;
        //     $data['modal_terjual']          = $penjualan;
        //     $data['retur_modal_terjual']    = $total_retur;
        //     $data['persediaan_akhir']       = $total_init + $dbp_pembelian - $retur_aop - $penjualan + $total_retur ;
        //     $data['status']                 = 'Y';
        //     $data['crea_date']              = Carbon::now();

        //     //dd($data);
        //     $inserted = NilaiPersediaan::create($data);

        //     if ($inserted) {
        //         return redirect()->route('pembelian-aop.proses')->with('success', 'Data persediaan berhasil ditambahkan.');

        //     } else {
        //         return redirect()->route('pembelian-aop.proses')->with('danger', 'Data persediaan gagal ditambahkan.');
        //     }
        // }  
        // elseif ($checkData !== null) {

        //     return redirect()->route('pembelian-aop.proses')->with('warning','Data persediaan sudah tersedia!');

        // }

        if ($checkData === null) {
            //Next Bulan
            $data['tanggal_awal']           = $tanggal_awal;
            $data['tanggal_akhir']          = $tanggal_akhir;
            $data['bulan']                  = $bulan;
            $data['tahun']                  = $tahun;
            $data['area_inv']               = $kd;
            $data['persediaan_awal']        = $getPersediaanAkhirPrev;
            $data['pembelian']              = $dbp_pembelian;
            $data['retur_aop']              = $retur_aop;
            $data['modal_terjual']          = $penjualan;
            $data['retur_modal_terjual']    = $total_retur;
            $data['persediaan_akhir']       = $getPersediaanAkhirPrev + $dbp_pembelian - $retur_aop - $penjualan + $total_retur ;
            $data['status']                 = 'Y';
            $data['crea_date']              = Carbon::now();
  
            //dd($data);
            $inserted = NilaiPersediaan::create($data);

            if ($inserted) {
                return redirect()->route('pembelian-aop.index')->with('success', 'Data persediaan berhasil ditambahkan!');

            } else {
                return redirect()->route('pembelian-aop.index')->with('danger', 'Data persediaan gagal ditambahkan!');
            }
        } 
        elseif ($checkData !== null) {

            return redirect()->route('pembelian-aop.index')->with('warning','Data persediaan sudah tersedia!');

        }
            
     }


}
