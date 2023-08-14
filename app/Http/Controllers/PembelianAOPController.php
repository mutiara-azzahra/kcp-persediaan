<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

// use App\Models\DbpAop;
use App\Models\Kode;
use App\Models\InitMasterPart;
use App\Models\ReturAOPDetails;
use App\Models\ReturAOPHeader;
use App\Models\PartAOPMaster;
use App\Models\TransaksiInvoiceDetails;
use App\Models\InvoiceAOPDetails;
use App\Models\InvoiceAOPHeader;
use App\Models\NilaiPersediaan;
// use App\Models\MstInit;
use App\Models\PartAOPDbp;
use App\Models\TransaksiReturHeader;
use App\Models\SuratTagihan;


class PembelianAOPController extends Controller
{
    public function index(){

        return view('pembelian-aop.index');
    }
    
    public function proses(){

        $persediaan = NilaiPersediaan::all();

        return view('pembelian-aop.proses', compact('persediaan'));
    }

    public function prosesPersediaan(Request $request){

        $tanggal_awal   = $request->input('tanggal_awal');

        //if tanggal akhir <1 bulan
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
                    isset($h->qty, $h->dbp->het, $h->master->level->diskon) &&
                    is_numeric($h->qty) && is_numeric($h->dbp->het) && is_numeric($h->master->level->diskon)
                ) {
                    $dbp = $h->qty * ($h->dbp->het * (100 - $h->master->level->diskon)/100) / 1.11;
                    $dbp_pembelian += $dbp;

                    dd($h->dbp);
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

        $penjualan = 0;
        
        foreach($getPenjualan as $p){

            $kurang_disc_ppn = $p->qty * ($p->master->dbp->het * ((100 - $p->master->level->diskon)/100) /1.11);

            $penjualan += $kurang_disc_ppn;

        }

        //PENJUALAN OPSI 2

        $penjualan_1 = 0;

        foreach($getPenjualan as $p) {
            $penjualan_dbp = $p->qty * $p->dbp_jual->het;
            $penjualan_1 += $penjualan_dbp;

        }

        //RETUR PENJUALAN
        $getReturPenjualan = TransaksiReturHeader::whereBetween('flag_approve1_date', [$tanggal_awal, $tanggal_akhir])
            ->where('area_retur', $kode->kode_area)
            ->where('flag_approve1', 'Y')
            ->get();

        $total_retur = 0;

        foreach ($getReturPenjualan as $r) {

            $total_retur += $r->details_retur->nominal_total/1.11;
        }

        //INSERT KE TABEL NILAI PERSEDIAAN
        $checkData = NilaiPersediaan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('area_inv', $kd)
            ->first();

        $getPersediaanAkhirPrev = NilaiPersediaan::where('bulan', $bulan_prev)->where('area_inv', $kd)->pluck('persediaan_akhir')->first();       

        if ($checkData === null) {

            //init Januari 2023
            $data['bulan']                  = $bulan;
            $data['tahun']                  = $tahun;
            $data['area_inv']               = $kd;
            $data['persediaan_awal']        = $total_init;
            $data['pembelian']              = $dbp_pembelian;
            $data['retur_aop']              = $retur_aop;
            $data['modal_terjual']          = $penjualan;
            $data['retur_modal_terjual']    = $total_retur;
            $data['persediaan_akhir']       = $total_init + $dbp_pembelian - $retur_aop - $penjualan + $total_retur ;
            $data['status']                 = 'Y';
            $data['crea_date']              = Carbon::now();

            dd($data);
            //$inserted = NilaiPersediaan::create($data);

            if ($inserted) {
                return redirect()->route('pembelian-aop.proses')->with('success', 'Data persediaan berhasil ditambahkan.');

            } else {
                return redirect()->route('pembelian-aop.proses')->with('danger', 'Data persediaan gagal ditambahkan.');
            }
        }  
        elseif ($checkData !== null) {

            return redirect()->route('pembelian-aop.proses')->with('warning','Data persediaan sudah tersedia!');

        }

        if ($checkData === null) {
            //Next Bulan
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
            //$inserted = NilaiPersediaan::create($data);

            if ($inserted) {
                return redirect()->route('pembelian-aop.proses')->with('success', 'Data persediaan berhasil ditambahkan!');

            } else {
                return redirect()->route('pembelian-aop.proses')->with('danger', 'Data persediaan gagal ditambahkan!');
            }
        } 
        elseif ($checkData !== null) {

            return redirect()->route('pembelian-aop.proses')->with('warning','Data persediaan sudah tersedia!');

        }
            
    }


}
